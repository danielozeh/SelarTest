<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Auth\AuthInterface::class,
            \App\Repositories\Auth\AuthRepository::class,
        );

        $this->app->bind(
            \App\Repositories\User\UserInterface::class,
            \App\Repositories\User\UserRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
