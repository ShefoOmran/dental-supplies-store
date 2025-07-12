<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductService;
use App\Services\CacheService;
use App\Services\FileUploadService;
use App\Services\ImageOptimizationService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        // Bind services
        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService(
                $app->make(ProductRepositoryInterface::class),
                $app->make(CacheService::class),
                $app->make(FileUploadService::class),
                $app->make(ImageOptimizationService::class)
            );
        });

        $this->app->singleton(CacheService::class);
        $this->app->singleton(FileUploadService::class);
        $this->app->singleton(ImageOptimizationService::class);
    }

    public function boot(): void
    {
        //
    }
} 