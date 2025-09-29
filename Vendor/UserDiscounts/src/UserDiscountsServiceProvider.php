<?php
namespace Vendor\UserDiscounts;

use Illuminate\Support\ServiceProvider;
use Vendor\UserDiscounts\Services\DiscountManager;

class UserDiscountsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/user-discounts.php','user-discounts');

        $this->app->singleton(DiscountManager::class, function($app) {
            return new DiscountManager($app['config']['user-discounts']);
        });
    }

    public function boot()
    {
        $this->publishes([__DIR__.'/../config/user-discounts.php' => config_path('user-discounts.php')], 'config');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
