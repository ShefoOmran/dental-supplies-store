<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Toothbrushes', 'description' => 'Manual and electric toothbrushes'],
            ['name' => 'Toothpaste', 'description' => 'Whitening, sensitive, and kids toothpaste'],
            ['name' => 'Mouthwash', 'description' => 'Antiseptic and fluoride mouthwashes'],
            ['name' => 'Floss', 'description' => 'Dental floss and picks'],
            ['name' => 'Accessories', 'description' => 'Other dental care accessories'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active' => true,
            ]);
        }
    }
}
