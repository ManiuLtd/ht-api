<?php

namespace App\Console\Commands\Spider;

use App\Jobs\Haohuo;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;

class HaohuoZC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider-haohuo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '好货专场';
    /**
     * @var
     */
    protected $TBK;

    /**
     * HaohuoZC constructor.
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
        $this->info('正在爬取好货专场');
        $totalPage = 2;
        $bar = $this->output->createProgressBar($totalPage);
        $min_id = 1;
        for ($i=1;$i<$totalPage;$i++){
            $this->info($min_id);
            $result = $this->TBK->HaohuoZC(['min_id'=>$min_id]);
            $result = json_decode($result);
            if($result->code != 1){
                return;
            }
            // 队列
            Haohuo::dispatch($result->data);
            $min_id = $result->min_id;
            $bar->advance();
            $this->info(">>>已采集完第{$i}页 ");
        }
        $bar->finish();
    }
}
