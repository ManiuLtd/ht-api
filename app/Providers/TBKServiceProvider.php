<?php

namespace App\Providers;

use App\Console\Commands\Spider\HaohuoZC;
use App\Console\Commands\Spider\JingDongOrder;
use App\Console\Commands\Spider\JingxuanDP;
use App\Console\Commands\Spider\JingxuanZT;
use App\Console\Commands\Spider\KuaiqiangShop;
use App\Console\Commands\Spider\PinDuoDuoOrder;
use App\Console\Commands\Spider\TaoBaoOrder;
use App\Http\Controllers\Frontend\HomeController;
use App\Tools\Taoke\TBKInterface;
use App\Console\Commands\Spider\Taobao;
use Illuminate\Support\ServiceProvider;
use App\Console\Commands\Spider\JingDong;
use App\Console\Commands\Spider\PinDuoDuo;
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
        $this->app->when(TaoBaoOrder::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);
        $this->app->when(JingDongOrder::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\JingDong::class);
        $this->app->when(PinDuoDuoOrder::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\PinDuoDuo::class);

        //好货专场 spider
        $this->app->when(HaohuoZC::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);
        //精选单品 spider
        $this->app->when(JingxuanDP::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);
        //精选专题 spider
        $this->app->when(JingxuanZT::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);
        //快抢商品 spider
        $this->app->when(KuaiqiangShop::class)
            ->needs(TBKInterface::class)
            ->give(\App\Tools\Taoke\Taobao::class);


        //测试
//        $this->app->when(HomeController::class)
//            ->needs(TBKInterface::class)
//            ->give(\App\Tools\Taoke\Taobao::class);
    }
}
