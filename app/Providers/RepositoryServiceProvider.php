<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
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
        $this->app->bind(\App\Repositories\Interfaces\BannerRepository::class, \App\Repositories\Image\BannerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\UserRepository::class, \App\Repositories\User\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopCouponRepository::class, \App\Repositories\Shop\ShopCouponRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopCouponCategoryRepository::class, \App\Repositories\Shop\ShopCouponCategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopCouponLogRepository::class, \App\Repositories\Shop\ShopCouponLogRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopOrderRepository::class, \App\Repositories\Shop\ShopOrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopGoodsRepository::class, \App\Repositories\Shop\ShopGoodsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopCategoryRepository::class, \App\Repositories\Shop\ShopCategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopOrderGoodsRefundRepository::class, \App\Repositories\Shop\ShopOrderGoodsRefundRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopGoodsTagRepository::class, \App\Repositories\Shop\ShopGoodsTagRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\ShopGoodsCommentRepository::class, \App\Repositories\Shop\ShopGoodsCommentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\RechargeRepository::class, \App\Repositories\Member\MemberRechargeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\WithdrawRepository::class, \App\Repositories\Member\MemberWithdrawRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberRepository::class, \App\Repositories\Member\MemberRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberLevelRepository::class, \App\Repositories\Member\MemberLevelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberAddressRepository::class, \App\Repositories\Member\MemberAddressRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberFavouriteRepository::class, \App\Repositories\Member\MemberFavouriteRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberHistoryRepository::class, \App\Repositories\Member\MemberHistoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberCreditLogRepository::class, \App\Repositories\Member\MemberCreditLogRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\SettingRepository::class, \App\Repositories\SettingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberAddressRepository::class, \App\Repositories\Member\MemberAddressRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberFavouriteRepository::class, \App\Repositories\Member\MemberFavouriteRepositoryEloquent::class);
        //:end-bindings:
    }
}
