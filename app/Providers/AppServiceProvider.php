<?php

namespace App\Providers;

use Fruitcake\Cors\HandleCors;
use Illuminate\Contracts\Http\Kernel;
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

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        /**
         * @var $kernel \Illuminate\Foundation\Http\Kernel
         */
        $kernel = $this->app->make(Kernel::class);
        if (!$this->app->runningInConsole() && !$this->app->isProduction()) {
            $kernel->pushMiddleware(HandleCors::class);
        }
    }
}
