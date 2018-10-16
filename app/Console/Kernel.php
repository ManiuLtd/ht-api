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
        //每5分钟更新
        $schedule->command('spider:tb --type=paoliang --all=false')->hourlyAt(50);
        $schedule->command('spider:tb --type=top100 --all=false')->hourlyAt(30);
        //每10分钟更新
        $schedule->command('spider:tb --type=total --all=false')->hourlyAt(9);
        //京东
        $schedule->command('spider:jd')->everyThirtyMinutes();

        $schedule->command('spider:pdd')->everyThirtyMinutes();




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
