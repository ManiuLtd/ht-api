<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveZhuanTis;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

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
     * @var TBKInterface
     */
    protected $TBK;

    /**
     * Create a new command instance.
     *
     * @return void
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
        $res = $this->TBK->JingxuanZT();
        try {
            foreach ($res->data as $re){
                $insert = [
                    'title'      => $re->name,
                    'thumb'      => $re->app_image,
                    'banner'     => $re->image,
                    'content'    => $re->content,
                    'start_time' => date('Y-m-d H:i:s',$re->activity_start_time),
                    'end_time'   => date('Y-m-d H:i:s',$re->activity_end_time),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
                db('tbk_zhuanti')->updateOrInsert([
                    'title' => $re->name
                ],$insert);
            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }
}
