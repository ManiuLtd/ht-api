<?php

namespace App\Providers;

use App\Console\Commands\Spider\JingDong;
use App\Console\Commands\Spider\PinDuoDuo;
use App\Console\Commands\Spider\Taobao;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Support\ServiceProvider;

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

    }
}
