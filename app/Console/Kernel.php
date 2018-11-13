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
        $schedule->command('spider:tb ')->everyThirtyMinutes();
        //每十分钟执行一次任务  处理优惠券
        $schedule->command('coupon-tool --type=2')->everyTenMinutes();
        //每小时第 n 分钟执行一次任务
        $schedule->command('spider:tb --type=1 --all=false')->hourlyAt(50);
        $schedule->command('spider:tb --type=top100 --all=false')->hourlyAt(30);
        //
        $schedule->command('spider:tb --type=3 --all=false')->hourlyAt(9);
        //京东
        $schedule->command('spider:jd')->everyThirtyMinutes();
        $schedule->command('spider:jd order')->everyFiveMinutes();

        $schedule->command('spider:pdd')->everyThirtyMinutes();
        $schedule->command('spider:pdd order')->everyThirtyMinutes();
        //淘宝订单
        //以创建时间查询
        $schedule->command('spider:tb order --type=1')->everyMinute();
        //以结算时间查询
        $schedule->command('spider:tb order --type=2')->everyMinute();
        //快抢
        $schedule->command('spider:tb kuaiqiang')->hourly();
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
