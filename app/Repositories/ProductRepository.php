<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 20): LengthAwarePaginator;
    public function findById(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function getFeatured(): Collection;
    public function create(array $data): Product;
    public function update(Product $product, array $data): bool;
    public function delete(Product $product): bool;
    public function search(string $query): Collection;
}

class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function getAll(): Collection
    {
        return $this->model->with(['category', 'images'])->get();
    }

    public function getPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->with(['category', 'images'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with('images')->where('slug', $slug)->first();
    }

    public function getFeatured(): Collection
    {
        return $this->model->with(['images'])
            ->where('featured', true)
            ->take(4)
            ->get();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function search(string $query): Collection
    {
        return $this->model->with(['category', 'images'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('brand', 'like', "%{$query}%")
            ->get();
    }
} 