<?php

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

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
