<?php

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

class JingxuanDP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider-danpin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '精选单品';

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
