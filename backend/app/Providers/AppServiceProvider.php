<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure the filesystem 'files' binding exists early in the container.
        // Some bootstrapping code (event discovery, view loading, etc.) may
        // attempt to resolve 'files' before the filesystem service provider
        // has registered. Provide a safe fallback binding here.
        $this->app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
