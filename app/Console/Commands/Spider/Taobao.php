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
    protected $signature = 'spider:tb {--type=total} {--all=true}';

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
        parent::__construct ();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //数据类型
        $type = $this->option ('type');
        //是否爬取所有
        $all = $this->option ('all');

        $this->info ('正在爬取大淘客优惠券');
        //开始爬取
        try {
            $params = [
                'type' => $type,
                'all' => $all,
            ];

            $result = $this->tbk->spider ($params);
            $total = $result['total'] ?? 0;
            $totalPage = $result['totalPage'] ?? 0;

            $this->info ("优惠券总数:{$total}");
            $this->info ("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar ($totalPage);

            for ($page = 1; $page <= $totalPage; $page++) {
                $params['page'] = $page;
                $response = $this->tbk->spider ($params);
                $result = $response['data'];

                if ($result) {
                    SaveGoods::dispatch ($result, 'taobao', $type, $all);
                }
                $bar->advance ();
                $this->info (" >>>已采集完第{$page}页");
            }
        } catch (\Exception $e) {
            $this->warn ($e->getMessage ());
        }
    }
}
