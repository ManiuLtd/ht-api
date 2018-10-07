<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;

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
     * @var
     */
    protected $tbk;

    /**
     * Taobao constructor.
     * @param TBKInterface $tbk
     */
    public function __construct(TBKInterface $tbk)
    {

        $this->tbk = $tbk;
        parent::__construct ();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 代码参考之前项目  爬取大淘客，地址http://www.dataoke.com/pmc/api-help.html
        //大淘客账号 18538549898 密码 123456@@

        //需要爬取这个地址里面的 http://www.dataoke.com/pmc/api-help.html  3、4、5
        //对应的数据库为 tbk_coupons ，type=1 ，tag 分别为 total  top100  paoliang

        //数据类型
        $type = $this->option('type');
        //是否爬取所有
        $all = $this->option('all');

        $this->info('正在爬取大淘客优惠券');
        //开始爬取

        $result = $this->tbk->spider(['type'=>$type,'all'=>$all]);

        if ($result['code'] == 4001) {
            $this->warn($result['message']);

            return;
        }

        $total = data_get($result, 'data.total', 0);
        $totalPage = data_get($result, 'data.totalPage', 0);

        $this->info("优惠券总数:{$total}");
        $this->info("总页码:{$totalPage}");
        $bar = $this->output->createProgressBar($totalPage);

        for ($page = 1; $page <= $totalPage; $page++) {

            $array = [
                'type' => $type,
                'all' => $all,
                'page' => $page,
            ];

            $response = $this->tbk->spider($array);

            if ($response['code'] == 4001) {
                $this->warn($response['message']);

                return;
            }
            $result = data_get($response, 'data.result', null);

            if ($result) {
                SaveGoods::dispatch($result, 'taobao', $type, $all);
            }

            $bar->advance();
            $this->info(" >>>已采集完第{$page}页");
        }
    }

}
