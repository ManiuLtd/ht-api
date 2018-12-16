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
        $this->app->bind(\App\Repositories\Interfaces\Shop\CouponRepository::class, \App\Repositories\Shop\CouponRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CouponCategoryRepository::class, \App\Repositories\Shop\CouponCategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CouponLogRepository::class, \App\Repositories\Shop\CouponLogRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\OrderRepository::class, \App\Repositories\Shop\OrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\GoodsRepository::class, \App\Repositories\Shop\GoodsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\CategoryRepository::class, \App\Repositories\Shop\CategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\RefundOrderRepository::class, \App\Repositories\Shop\RefundOrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\GoodsTagRepository::class, \App\Repositories\Shop\GoodsTagRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Shop\GoodsCommentRepository::class, \App\Repositories\Shop\GoodsCommentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\RechargeRepository::class, \App\Repositories\User\RechargeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\WithdrawRepository::class, \App\Repositories\User\WithdrawRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\UserRepository::class, \App\Repositories\User\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\LevelRepository::class, \App\Repositories\User\LevelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\AddressRepository::class, \App\Repositories\User\AddressRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\HistoryRepository::class, \App\Repositories\User\HistoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\CreditLogRepository::class, \App\Repositories\User\CreditLogRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\AddressRepository::class, \App\Repositories\User\AddressRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\FavouriteRepository::class, \App\Repositories\User\FavouriteRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\GroupRepository::class, \App\Repositories\User\GroupRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\User\PaymentRepository::class, \App\Repositories\User\PaymentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\System\SettingRepository::class, \App\Repositories\System\SettingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\System\NotificationRepository::class, \App\Repositories\System\NotificationRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\System\FeedbackRepository::class, \App\Repositories\System\FeedbackRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\System\SmsRepository::class, \App\Repositories\System\SmsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Cms\CategoriesRepository::class, \App\Repositories\Cms\CategoriesRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Cms\ProjectRepository::class, \App\Repositories\Cms\ProjectRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Cms\ArticleRepository::class, \App\Repositories\Cms\ArticleRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\CategoryRepository::class, \App\Repositories\Taoke\CategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\CouponRepository::class, \App\Repositories\Taoke\CouponRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\PidRepository::class, \App\Repositories\Taoke\PidRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\OrderRepository::class, \App\Repositories\Taoke\OrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\FavouriteRepository::class, \App\Repositories\Taoke\FavouriteRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\HistoryRepository::class, \App\Repositories\Taoke\HistoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\UserOrderRepository::class, \App\Repositories\Taoke\UserOrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\HaoHuoRepository::class, \App\Repositories\Taoke\HaoHuoRepositoryEloquent::class);

        $this->app->bind(\App\Repositories\Interfaces\Taoke\ZhuanTiRepository::class, \App\Repositories\Taoke\ZhuanTiRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\EntranceRepository::class, \App\Repositories\Taoke\EntranceRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\EntranceCategoryRepository::class, \App\Repositories\Taoke\EntranceCategoryRepositoryEloquent::class);

        $this->app->bind(\App\Repositories\Interfaces\Taoke\JingXuanRepository::class, \App\Repositories\Taoke\JingXuanRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\KuaiQiangRepository::class, \App\Repositories\Taoke\KuaiQiangRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\OauthRepository::class, \App\Repositories\Taoke\OauthRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\SettingRepository::class, \App\Repositories\Taoke\SettingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\DianRepository::class, \App\Repositories\Taoke\DianRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\DianCategoriesRepository::class, \App\Repositories\Taoke\DianCategoriesRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\DianTagRepository::class, \App\Repositories\Taoke\DianTagRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\DianOrderRepository::class, \App\Repositories\Taoke\DianOrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\DiyZhuantiRepository::class, \App\Repositories\Taoke\DiyZhuantiRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\Taoke\DiyAdsRepository::class, \App\Repositories\Taoke\DiyAdsRepositoryEloquent::class);
        //:end-bindings:
    }
}
