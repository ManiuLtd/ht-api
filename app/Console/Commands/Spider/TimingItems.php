<?php

namespace App\Console\Commands\Spider;

use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;

class TimingItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timing-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时拉取';
    /**
     * @var
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
        $totalPage = 100;
        $bar = $this->output->createProgressBar($totalPage);
        $min_id = 1;
        for ($i=1;$i<$totalPage;$i++){
            $this->info($min_id);
            $result = $this->TBK->TimingItems(['min_id'=>$min_id]);
            $result = json_decode($result);
            if($result->code != 1){
                return;
            }
            // 队列
//            Haohuo::dispatch($result->data);
            $min_id = $result->min_id;
            $bar->advance();
            $this->info(">>>已采集完第{$i}页 ");
        }
        $bar->finish();
    }
}
