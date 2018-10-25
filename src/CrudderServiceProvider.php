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
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Kakposoe\Crudder\CrudderController');
    }
}
