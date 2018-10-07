<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Console\Command;

class JingDong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:jd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '京东优惠券爬虫';

    /**
     * @var
     */
    protected $appid;
    /**
     * @var
     */
    protected $appkey;
    /**
     * @var
     */
    protected $applisturl;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->appid = data_get(config('coupon'), 'jingdong.JD_APPID');
        $this->appkey = data_get(config('coupon'), 'jingdong.JD_APPKEY');
        $this->applisturl = data_get(config('coupon'), 'jingdong.JD_LIST_APPURL');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO 京东爬虫 爬取京推推
        // http://www.jingtuitui.com/  账号密码 15538762226  372945452zz

        $this->info('正在爬取京推推优惠券');
        $result = $this->JTTSpider();
        if ($result['code'] == 4001) {
            $this->warn($result['message']);

            return;
        }
        $totalPage = data_get($result, 'data.totalPage', 1);

        $this->info("总页码:{$totalPage}");
        $bar = $this->output->createProgressBar($totalPage);

        for ($page = 1; $page <= $totalPage; $page++) {
            $response = $this->JTTSpider($page);
            if ($result['code'] == 4001) {
                $this->warn($result['message']);

                return;
            }
            $data = data_get($response, 'data.data', null);

            if ($data) {
                SaveGoods::dispatch($data, 'jingdong');
            }
            $bar->advance();
            $this->info(">>>已采集完第{$page}页 ");
        }
        $bar->finish();
    }

    /**
     * @param int $page
     * @return array
     */
    protected function JTTSpider($page = 1)
    {
        $params = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'num' => 100,
            'page' => $page,
        ];
        $response = Curl::to($this->applisturl)
            ->withData($params)
            ->post();
        $response = json_decode($response);
        if ($response->return != 0) {
            return [
                'code' => 4001,
                'message' => $response->result,
            ];
        }

        return [
            'code' => 1001,
            'message' => '优惠券获取成功',
            'data' => [
                'totalPage' => $response->result->total_page,
                'data' => $response->result->data,
            ],

        ];
    }
}
