<?php

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

class UpdateItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '商品更新';

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
