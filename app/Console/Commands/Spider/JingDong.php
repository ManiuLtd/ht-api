<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;

use App\Tools\Taoke\TBKInterface;
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

    protected $JingDong;

    /**
     * JingDong constructor.
     * @param TBKInterface $TBK
     */
    public function __construct(TBKInterface $TBK)
    {

        $this->JingDong = $TBK;
        parent::__construct ();

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


        $this->info ("正在爬取京推推优惠券");
        $result = $this->JingDong->spider ();

        if ($result['code'] == 4001) {
            $this->warn($result['message']);

            return;
        }
        $totalPage = data_get($result, 'data.totalPage', 1);

        $this->info("总页码:{$totalPage}");
        $bar = $this->output->createProgressBar($totalPage);

        for ($page = 1; $page <= $totalPage; $page++) {


            $response = $this->JingDong->spider(['page'=>$page]);

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


}
