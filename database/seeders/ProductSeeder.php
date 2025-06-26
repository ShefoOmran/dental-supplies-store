<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'UltraSoft Toothbrush',
                'slug' => 'ultrasoft-toothbrush',
                'description' => 'A gentle toothbrush for sensitive gums.',
                'price' => 3.99,
                'stock' => 100,
                'sku' => 'TB-001',
                'is_active' => true,
                'featured' => true,
                'brand' => 'DentalPro',
            ],
            [
                'category_id' => 2,
                'name' => 'Whitening Toothpaste',
                'slug' => 'whitening-toothpaste',
                'description' => 'Removes stains and whitens teeth.',
                'price' => 4.99,
                'stock' => 200,
                'sku' => 'TP-001',
                'is_active' => true,
                'featured' => false,
                'brand' => 'BrightSmile',
            ],
            [
                'category_id' => 3,
                'name' => 'Mint Mouthwash',
                'slug' => 'mint-mouthwash',
                'description' => 'Freshens breath and kills bacteria.',
                'price' => 5.99,
                'stock' => 150,
                'sku' => 'MW-001',
                'is_active' => true,
                'featured' => false,
                'brand' => 'FreshMint',
            ],
            [
                'category_id' => 4,
                'name' => 'Waxed Dental Floss',
                'slug' => 'waxed-dental-floss',
                'description' => 'Slides easily between teeth.',
                'price' => 2.99,
                'stock' => 300,
                'sku' => 'FL-001',
                'is_active' => true,
                'featured' => false,
                'brand' => 'CleanLine',
            ],
            [
                'category_id' => 5,
                'name' => 'Tongue Cleaner',
                'slug' => 'tongue-cleaner',
                'description' => 'Removes bacteria from the tongue.',
                'price' => 1.99,
                'stock' => 250,
                'sku' => 'AC-001',
                'is_active' => true,
                'featured' => false,
                'brand' => 'OralCare',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
