<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveOrders;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;

class PinDuoDuoOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:pdd-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var
     */
    protected $TBK;

    /**
     * PinDuoDuoOrder constructor.
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
        $resp = $this->TBK->getOrders();
        if ($resp['code'] != 1001){
            $this->warn($resp['message']);
            return;
        }
        $total_count = data_get($resp, 'data.total_count', 1);
        $totalPage = (int) ceil($total_count / 50);
        $this->info("总页码:{$totalPage}");
        $bar = $this->output->createProgressBar($totalPage);

        for ($page = 1; $page <= $totalPage; $page++) {
            $response = $this->TBK->getOrders(['page'=>$page]);

            if ($response['code'] != 1001) {
                $this->warn($response['message']);
                return;
            }
            $data = data_get($response, 'data.order_list', null);

            if ($data) {
                SaveOrders::dispatch($data, 'pinduoduo');
            }
            $bar->advance();
            $this->info(">>>已采集完第{$page}页 ");
        }
        $bar->finish();

    }
}
