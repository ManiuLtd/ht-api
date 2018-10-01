<?php

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

class Taobao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:tb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '淘宝优惠券爬虫';

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
        $this->info ('Hello World');
        //TODO 代码参考之前项目  爬取大淘客，地址http://www.dataoke.com/pmc/api-help.html
        //大淘客账号 18538549898 密码 123456@@

        //需要爬取这个地址里面的 http://www.dataoke.com/pmc/api-help.html  3、4、5
        //对应的数据库为 tbk_coupons ，type=1 ，tag 分别为 total  top100  paoliang
    }
}
