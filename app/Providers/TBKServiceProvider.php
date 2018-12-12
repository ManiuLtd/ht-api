<?php

namespace App\Providers;

use App\Http\Controllers\Api\Taoke\CategoriesController;
use App\Http\Controllers\Api\Taoke\GuessController;
use App\Tools\Taoke\TBKInterface;
use App\Console\Commands\Spider\Taobao;
use Illuminate\Support\ServiceProvider;
use App\Console\Commands\Spider\JingDong;
use App\Console\Commands\Spider\PinDuoDuo;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Api\Taoke\SearchController;
use App\Http\Controllers\Api\Taoke\CouponsController;

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

        //优惠券详情
        switch (request('type')) {
            case '1':
                $this->app->when(CouponsController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\Taobao::class);
                break;
            case '2':
                $this->app->when(CouponsController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\JingDong::class);
                break;
            case '3':
                $this->app->when(CouponsController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\PinDuoDuo::class);
                break;
            default:
                $this->app->when(CouponsController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\Taobao::class);
                break;
        }

        //优惠券搜索
        switch (request('type')) {
            case '1':
                $this->app->when(SearchController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\Taobao::class);

                break;
            case '2':
                $this->app->when(SearchController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\JingDong::class);
                break;
            case '3':
                $this->app->when(SearchController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\PinDuoDuo::class);
                break;
            default:
                $this->app->when(SearchController::class)
                    ->needs(TBKInterface::class)
                    ->give(\App\Tools\Taoke\Taobao::class);
                break;
        }

        //热搜词
        if (request('hot') == 1) {
            $this->app->when(SearchController::class)
                ->needs(TBKInterface::class)
                ->give(\App\Tools\Taoke\Taobao::class);
        }
        //订单

        //测试
//        $this->app->when(HomeController::class)
//            ->needs(TBKInterface::class)
//            ->give(\App\Tools\Taoke\Taobao::class);

        $this->app->when(CategoriesController::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);

    }
}
