<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;

class CacheService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function getProducts($page = 1)
    {
        $cacheKey = "products_page_{$page}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($page) {
            return Product::with(['category', 'images'])
                ->orderBy('created_at', 'desc')
                ->paginate(20, ['*'], 'page', $page);
        });
    }

    public function getCategories()
    {
        return Cache::remember('categories_active', self::CACHE_TTL, function () {
            return Category::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
    }

    public function getFeaturedProducts()
    {
        return Cache::remember('products_featured', self::CACHE_TTL, function () {
            return Product::with(['images'])
                ->where('featured', true)
                ->take(4)
                ->get();
        });
    }

    public function getProductBySlug($slug)
    {
        $cacheKey = "product_slug_{$slug}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($slug) {
            return Product::with('images')->where('slug', $slug)->firstOrFail();
        });
    }

    public function clearProductCache($productId = null)
    {
        // Clear all product-related caches
        Cache::forget('products_featured');
        
        // Clear paginated caches
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("products_page_{$i}");
        }

        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                Cache::forget("product_slug_{$product->slug}");
            }
        }
    }

    public function clearCategoryCache()
    {
        Cache::forget('categories_active');
    }

    public function warmCache()
    {
        // Pre-load frequently accessed data
        $this->getCategories();
        $this->getFeaturedProducts();
        $this->getProducts(1);
    }
} 