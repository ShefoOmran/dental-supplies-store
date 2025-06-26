<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class DummyProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyImage = 'dummy.jpg'; // Place a dummy.jpg in public/storage/products/
        foreach (Product::all() as $product) {
            if ($product->images()->count() === 0) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'products/' . $dummyImage,
                    'is_primary' => true,
                    'alt_text' => $product->name,
                ]);
            }
        }
    }
}
