<?php

namespace App\Library\Debug;

use Illuminate\Support\ServiceProvider;

class AppDebugProvider extends ServiceProvider {

    /**
     * Bootstrap application service.
     */
    public function boot()
    {
        if ($this->app['config']['app.library.debug.enable'])
        {
            $this->app->make(AppDebug::class)->collectDatabaseQueries();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AppDebug::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            AppDebug::class
        ];
    }
}