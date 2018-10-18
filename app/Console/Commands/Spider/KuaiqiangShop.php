<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveKuaiqiang;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;

class KuaiqiangShop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider-kuaiqing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快抢商品';
    /**
     * @var
     */
    protected $TBK;

    /**
     * KuaiqiangShop constructor.
     * @param TBKInterface $TBK
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
        $total = 50 ;
        $min_id = 1;
        $bar = $this->output->createProgressBar($total);
        for($i = 1;$i <= $total; $i ++)
        {
            $this->info($min_id);
            $kuaiqiang = $this->TBK->KuaiqiangShop(['min_id'=>$min_id]);
            if($kuaiqiang->code != 1)
            {
                $this->warn($kuaiqiang->msg);
                return;
            }
            $min_id = $kuaiqiang->min_id;
            $bar->advance();
            SaveKuaiqiang::dispatch($kuaiqiang->data);
            $this->info(">>>已采集完第{$total}页");
        }
        $bar->finish();

    }
}
