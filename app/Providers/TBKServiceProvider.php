<?php

namespace App\Providers;

use App\Http\Controllers\Frontend\HomeController;
use App\Tools\Taoke\TBKInterface;
use App\Console\Commands\Spider\Taobao;
use Illuminate\Support\ServiceProvider;
use App\Console\Commands\Spider\JingDong;
use App\Console\Commands\Spider\PinDuoDuo;

class TBKServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //淘宝 spider
        $this->app->when(Taobao::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);
        //京东spider
        $this->app->when(JingDong::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\JingDong::class);
        //拼多多spider
        $this->app->when(PinDuoDuo::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\PinDuoDuo::class);

        switch (request ('type')){
            case '1':
                $this->app->when(PinDuoDuo::class)  //这里不变
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\PinDuoDuo::class);  //这里变
        }
        //测试
//        $this->app->when(HomeController::class)
//            ->needs(TBKInterface::class)
//            ->give(\App\Tools\Taoke\PinDuoDuo::class);
    }
}
