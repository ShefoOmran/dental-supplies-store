<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            ['product_id' => 1, 'image_path' => 'images/products/toothbrush.jpg', 'is_primary' => true, 'alt_text' => 'UltraSoft Toothbrush'],
            ['product_id' => 2, 'image_path' => 'images/products/toothpaste.jpg', 'is_primary' => true, 'alt_text' => 'Whitening Toothpaste'],
            ['product_id' => 3, 'image_path' => 'images/products/mouthwash.jpg', 'is_primary' => true, 'alt_text' => 'Mint Mouthwash'],
            ['product_id' => 4, 'image_path' => 'images/products/floss.jpg', 'is_primary' => true, 'alt_text' => 'Waxed Dental Floss'],
            ['product_id' => 5, 'image_path' => 'images/products/tongue-cleaner.jpg', 'is_primary' => true, 'alt_text' => 'Tongue Cleaner'],
        ];

        foreach ($images as $img) {
            ProductImage::create($img);
        }
    }
}
