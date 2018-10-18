<?php

namespace App\Console\Commands\Spider;

use App\Jobs\Spider\DownItem;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class DownItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '失效商品列表';

    /**
     * @var TBKInterface
     */
    protected $TBK;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TBKInterface $TBK)
    {
        $this->TBK = $TBK;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $end   = date('H');
//        if ($end == 0){
//            $end   = 23;
//        }
//        $start = $end - 1;
//        $res = $this->TBK->DownItems([
//            'start' => $start,
//            'end' => $end
//        ]);
//        foreach ($res->data as $result){
//            dd($result);
//            db('tbk_coupons')->where('item_id',$result->itemid)->delete();
//        }
//        DownItem::dispatch($res->data);
        $total = 50;
        $this->info('正在删除失效优惠卷！');
        $bar = $this->output->createProgressBar($total);
        $end   = date('H');
        if ($end == 0){
            $end   = 23;
        }
        $start = $end - 1;
        for ($i=1;$i <= $total; $i++) {
            $rest = $this->TBK->DownItems([
                'start' => $start,
                'end' => $end
            ]);
            // 队列
            DownItem::dispatch(data_get($rest,'data.data'));
            $bar->advance();
            $this->info(">>>已采集完第{$total}页 ");
        }
        $bar->finish();
    }
}
