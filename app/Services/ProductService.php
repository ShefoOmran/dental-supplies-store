<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function createProduct(array $data, $images = null)
    {
        $product = Product::create($data);

        if ($images) {
            $this->handleProductImages($product, $images);
        }

        return $product;
    }

    public function updateProduct(Product $product, array $data, $images = null, $replaceImages = false)
    {
        $product->update($data);

        if ($images) {
            if ($replaceImages) {
                $this->deleteProductImages($product);
            }
            $this->handleProductImages($product, $images);
        }

        return $product;
    }

    public function deleteProduct(Product $product)
    {
        $this->deleteProductImages($product);
        return $product->delete();
    }

    private function handleProductImages(Product $product, $images)
    {
        foreach ($images as $idx => $image) {
            $destination = public_path('storage/images/products');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $originalName = $image->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $counter = 1;
            
            while (file_exists($destination.'/'.$originalName)) {
                $originalName = $fileName.'_'.$counter.'.'.$extension;
                $counter++;
            }

            $image->move($destination, $originalName);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'storage/images/products/'.$originalName,
                'is_primary' => $idx === 0,
                'alt_text' => $product->name,
            ]);
        }
    }

    private function deleteProductImages(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }
} 