<?php

namespace App\Console\Commands\Spider;

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
        $this->TBK->HaohuoZC();
    }
}
