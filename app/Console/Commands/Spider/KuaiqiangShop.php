<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveKuaiqiang;
use App\Jobs\Spider\Kuaiqiang;
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
        $total = 50;
        $bar = $this->output->createProgressBar($total);
        $min_id = 1;
        for ($i=1;$i <= $total; $i++) {

            $rest = $this->TBK->KuaiqiangShop(['min_id'=>$min_id]);
            if ($rest['code'] != 1001) {
                $this->warn($rest['message']);

                return ;
            }
            // 队列
            $data = data_get($rest,'data.data');
            Kuaiqiang::dispatch($data);
            $min_id = data_get($rest,'data.min_id');

            $bar->advance();
            $this->info(">>>已采集完第{$total}页 ");
        }
        $bar->finish();

    }
}
