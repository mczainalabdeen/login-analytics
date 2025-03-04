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
        // Debug: Check the contents of the config file
        $config = require __DIR__.'/../config/login-analytics.php';
        if (!is_array($config)) {
            dd('Config file is not an array!', $config);  // Debugging line
        }

        // Merge the configuration
        $this->mergeConfigFrom(__DIR__.'/../config/login-analytics.php', 'login-analytics');
    }

}
