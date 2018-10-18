<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class Taobao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:tb {--type=3} {--all=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '大淘客爬虫';

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
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //数据类型
        $type = $this->option('type');
        //是否爬取所有
        $all = $this->option('all');

        $this->info('正在爬取大淘客优惠券');
        //开始爬取
        try {

            $totalPage = 9999999;
            if ($all == 'false') {
                $totalPage = 3;
            }

            $total = $totalPage * 100;
            $this->info("优惠券总数:{$total}");
            $this->info("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar($totalPage);

            $min_id = 1;
            for ($i = 1; $i <= $totalPage; $i++) {

                $response = $this->tbk->spider([
                    'type' => $type,
                    'min_id' => $min_id,
                ]);
                if ($response['code'] != 1001) {
                    $this->warn($response['message']);
                    return ;
                }
                $result = $response['data'];
                if ($result) {

                    SaveGoods::dispatch($result['data'], 'taobao', $type, $all);
                }
                $min_id = $result['min_id'];
                $bar->advance();
                $this->info(" >>>已采集完第{$i}页");
                
            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }
}
