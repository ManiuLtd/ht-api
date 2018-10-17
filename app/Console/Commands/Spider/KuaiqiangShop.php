<?php

namespace App\Console\Commands\Spider;

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
        //
    }
}
