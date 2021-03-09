<?php

namespace App\Providers;

use CKSource\CKFinder\CKFinder;
use Fruitcake\Cors\HandleCors;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ckfinder.connector', function() {
            if (!class_exists('\CKSource\CKFinder\CKFinder')) {
                throw new \Exception(
                    "Couldn't find CKFinder conector code. ".
                    "Please run `artisan ckfinder:download` command first."
                );
            }
            $ckfinderConfig = config('ckfinder');
            if (is_null($ckfinderConfig)) {
                throw new \Exception(
                    "Couldn't load CKFinder configuration file. ".
                    "Please run `artisan vendor:publish --tag=ckfinder` command first."
                );
            }
            $ckfinder = new CKFinder($ckfinderConfig);
            if (SymfonyKernel::MAJOR_VERSION === 4) {
                $this->setupForV4Kernel($ckfinder);
            }
            return $ckfinder;
        });
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
            if (!$kernel->hasMiddleware(HandleCors::class)) {
                $kernel->pushMiddleware(HandleCors::class);
            }
        }
        if (!$this->app->isProduction()) {
            DB::enableQueryLog();
        }
    }

    /**
     * Prepares CKFinder DI container to use version version 4+ of HttpKernel.
     *
     * @param CKFinder $ckfinder
     */
    protected function setupForV4Kernel(CKFinder $ckfinder)
    {
//        $ckfinder['resolver'] = function () use ($ckfinder) {
//            $commandResolver = new \CKSource\CKFinderBridge\Polyfill\CommandResolver($ckfinder);
//            $commandResolver->setCommandsNamespace(CKFinder::COMMANDS_NAMESPACE);
//            $commandResolver->setPluginsNamespace(CKFinder::PLUGINS_NAMESPACE);
//
//            return $commandResolver;
//        };

        $ckfinder['kernel'] = function () use ($ckfinder) {
            return new HttpKernel(
                $ckfinder['dispatcher'],
                $ckfinder['resolver'],
                $ckfinder['request_stack'],
                $ckfinder['resolver']
            );
        };
    }
}
