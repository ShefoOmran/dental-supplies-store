<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Tighten\Ziggy\Ziggy;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Inertia::share('ziggy', function () {
            return (new Ziggy)->toArray();
        });
    }
}