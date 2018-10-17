<?php

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

class JingxuanZT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider-zhuanti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '精选专题';

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
        //
    }
}
