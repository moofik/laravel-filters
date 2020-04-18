<?php

namespace Moofik\LaravelFilters;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Moofik\LaravelFilters\Commands\InstallCommand;
use Moofik\LaravelFilters\Repository\ModelDiscoverer;
use Moofik\LaravelFilters\Repository\ModelRepository;

class LaravelFiltersProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton(ModelRepository::class, function(Application $app) {
            return new ModelRepository(
                new Filesystem(),
                new ModelDiscoverer()
            );
        });
    }
}
