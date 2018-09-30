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
        $this->app->bind(\App\Repositories\Interfaces\Image\BannerRepository::class, \App\Repositories\Image\BannerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\UserRepository::class, \App\Repositories\User\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CouponRepository::class, \App\Repositories\Shop\CouponRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CouponCategoryRepository::class, \App\Repositories\Shop\CouponCategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CouponLogRepository::class, \App\Repositories\Shop\CouponLogRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\OrderRepository::class, \App\Repositories\Shop\OrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\GoodsRepository::class, \App\Repositories\Shop\GoodsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CategoryRepository::class, \App\Repositories\Shop\CategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\RefundOrderRepository::class, \App\Repositories\Shop\RefundOrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\GoodsTagRepository::class, \App\Repositories\Shop\GoodsTagRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\GoodsCommentRepository::class, \App\Repositories\Shop\GoodsCommentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\RechargeRepository::class, \App\Repositories\Member\RechargeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\WithdrawRepository::class, \App\Repositories\Member\WithdrawRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\MemberRepository::class, \App\Repositories\Member\MemberRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\LevelRepository::class, \App\Repositories\Member\LevelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\AddressRepository::class, \App\Repositories\Member\AddressRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\FavouriteRepository::class, \App\Repositories\Member\FavouriteRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\HistoryRepository::class, \App\Repositories\Member\HistoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\CreditLogRepository::class, \App\Repositories\Member\CreditLogRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\AddressRepository::class, \App\Repositories\Member\AddressRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Member\FavouriteRepository::class, \App\Repositories\Member\FavouriteRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\SettingRepository::class, \App\Repositories\SettingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Cms\CategoriesRepository::class, \App\Repositories\Cms\CategoriesRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Cms\ProjectRepository::class, \App\Repositories\Cms\ProjectRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Cms\ArticleRepository::class, \App\Repositories\Cms\ArticleRepositoryEloquent::class);
        //:end-bindings:
    }
}
