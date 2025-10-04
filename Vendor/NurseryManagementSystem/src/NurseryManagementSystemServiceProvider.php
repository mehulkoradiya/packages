<?php

namespace Vendor\NurseryManagementSystem;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class NurseryManagementSystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nms.php', 'nms');
    }

    public function boot(): void
    {
        // Register middleware alias for role checks
        Route::aliasMiddleware('nms.role', \Vendor\NurseryManagementSystem\Http\Middleware\RoleMiddleware::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nms');

        $this->publishes([
            __DIR__.'/../config/nms.php' => config_path('nms.php'),
        ], 'nms-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/nms'),
        ], 'nms-views');

        if (! class_exists('CreateNmsCoreTables')) {
            $this->publishes([
                __DIR__.'/../database/migrations/0001_01_01_000000_create_nms_core_tables.php' => database_path('migrations/'.date('Y_m_d_His').'_create_nms_core_tables.php'),
            ], 'nms-migrations');
        }
    }
}
