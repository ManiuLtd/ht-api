<?php

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

class PinDuoDuo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:pdd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拼多多优惠券爬虫';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO  拼多多怕爬虫 爬取多多进宝 http://jinbao.pinduoduo.com
    }
}
