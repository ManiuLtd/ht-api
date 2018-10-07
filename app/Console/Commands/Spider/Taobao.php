<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;

class Taobao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:tb {--type=total} {--all=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '淘宝优惠券爬虫';

    /**
     * @var mixed
     */
    protected $apiKey;

    /**
     * @var mixed
     */
    protected $apiUrl;

    /**
     * Taobao constructor.
     */
    public function __construct()
    {
        $this->apiKey = data_get (config ('coupon'), 'taobao.TB_API_KEY');
        $this->apiUrl = data_get (config ('coupon'), 'taobao.TB_API_URL');
        parent::__construct ();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO 代码参考之前项目  爬取大淘客，地址http://www.dataoke.com/pmc/api-help.html
        //大淘客账号 18538549898 密码 123456@@

        //需要爬取这个地址里面的 http://www.dataoke.com/pmc/api-help.html  3、4、5
        //对应的数据库为 tbk_coupons ，type=1 ，tag 分别为 total  top100  paoliang

        //数据类型
        $type = $this->option ('type');
        //是否爬取所有
        $all = $this->option ('all');

        $this->info ("正在爬取大淘客优惠券");
        //开始爬取
        $result = $this->DTKSpider ($type, $all);
        if ($result['code'] == 4001) {
            $this->warn ($result['message']);
            return;
        }

        $total = data_get ($result, 'data.total', 0);
        $totalPage = data_get ($result, 'data.totalPage', 0);

        $this->info ("优惠券总数:{$total}");
        $this->info ("总页码:{$totalPage}");
        $bar = $this->output->createProgressBar ($totalPage);

        for ($page = 1; $page <= $totalPage; $page++) {
            $response = $this->DTKSpider ($type, $all, $page);
            if ($response['code'] == 4001) {
                $this->warn ($response['message']);
                return;
            }
            $result = data_get ($response, 'data.result', null);

            if ($result) {
                SaveGoods::dispatch ($result, 'taobao', $type, $all);
            }

            $bar->advance ();
            $this->info (" >>>已采集完第{$page}页");
        }


    }

    /**
     * @param string $type
     * @param bool $all
     * @param int $page
     * @return array
     */
    protected function DTKSpider($type = 'total', $all = true, $page = 1)
    {
        $params = [
            'r' => 'Port/index',
            'appkey' => $this->apiKey,
            'v' => '2',
            'page' => $page
        ];
        //爬虫类型
        switch ($type) {
            case 'total':
                $params['type'] = 'total';
                break;
            case 'paoliang':
                $params['type'] = 'paoliang';
                break;
            case 'top100':
                $params['type'] = 'top100';
                break;
            default:
                $params['type'] = 'total';
                break;
        }
        $response = Curl::to ($this->apiUrl)
            ->withData ($params)
            ->get ();
        $response = json_decode ($response);

        //验证
        if (!isset($response->data)) {
            return [
                'code' => 4001,
                'message' => '接口内容获取失败'
            ];
        }
        $total = $response->data->total_num ?? 0;
        if ($total <= 0) {
            return [
                'code' => 4001,
                'message' => '没有获取到产品'
            ];
        }
        $totalPage = (int)ceil ($total / 50);
        //不爬取所有的
        if (!$all) {
            $totalPage = 3;
        }

        return [
            'code' => 1001,
            'message' => '优惠券获取成功',
            'data' => [
                'totalPage' => $totalPage,
                'total' => $total,
                'result' => $response->result,

            ],
        ];


    }
}
