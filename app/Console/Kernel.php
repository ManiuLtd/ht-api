<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //淘宝  半小时执行
        $schedule->command('spider:tb --type=3 --all=true')->dailyAt('01:30');
        $schedule->command('spider:jd --all=true')->dailyAt('01:30');

        $schedule->command('spider:pdd --all=true')->dailyAt('01:30');

        //更新优惠券状态
        $schedule->command('update_status')->dailyAt('2:30');

        //实时跑单商品
        $schedule->command('spider:tb --type=1 --all=false')->hourlyAt(50);
        //爆单榜商品
        $schedule->command('spider:tb --type=2 --all=false')->hourlyAt(30);
        //纯视频单
        $schedule->command('spider:tb --type=4 --all=false')->hourlyAt(9);
        //聚淘专区
        $schedule->command('spider:tb --type=5 --all=false')->hourlyAt(9);

        // 淘宝 全部商品
        $schedule->command('spider:tb --type=3 --all=false')->everyFiveMinutes();
        //京东
        $schedule->command('spider:jd --all=false')->everyFiveMinutes();
        //京东订单
        $schedule->command('spider:jd order')->everyFiveMinutes();
        //拼多多
        $schedule->command('spider:pdd --all=false')->everyFiveMinutes();
        
        $schedule->command('spider:pdd order')->everyThirtyMinutes();
        //淘宝订单
        //以创建时间查询
        $schedule->command('spider:tb order --type=1')->everyMinute();
        //以结算时间查询
        $schedule->command('spider:tb order --type=2')->everyMinute();
        //快抢
        $schedule->command('spider:tb kuaiqiang')->everyFiveMinutes();
        //定时拉取
        $schedule->command('spider:tb timingItems')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
