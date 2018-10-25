<?php

namespace Kakposoe\Crudder;

use Illuminate\Support\ServiceProvider;

class CrudderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/crudder.php' => config_path('crudder.php')
        ]);

        $this->loadViewsFrom(__DIR__ . '/views', 'crudder');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Kakposoe\Crudder\Controllers\CrudderController');
    }
}
