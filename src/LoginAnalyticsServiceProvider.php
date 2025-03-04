<?php

namespace LoginAnalytics;

use Illuminate\Support\ServiceProvider;
use LoginAnalytics\Http\Middleware\MonitorLoginActivity;

class LoginAnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register middleware
        $this->app['router']->aliasMiddleware('login.monitor', MonitorLoginActivity::class);

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/login-analytics.php' => config_path('login-analytics.php'),
        ], 'config');
    }

    public function register()
    {
        //$this->mergeConfigFrom(__DIR__.'/../config/login-analytics.php', 'login-analytics');
    }
}
