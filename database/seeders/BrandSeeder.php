<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Royal Canin',
                'description' => 'Premium pet nutrition for dogs and cats',
                'is_active' => true,
            ],
            [
                'name' => 'Hill\'s Science Diet',
                'description' => 'Veterinarian recommended pet food',
                'is_active' => true,
            ],
            [
                'name' => 'Purina Pro Plan',
                'description' => 'Advanced nutrition for active pets',
                'is_active' => true,
            ],
            [
                'name' => 'Kong',
                'description' => 'Interactive toys and enrichment products',
                'is_active' => true,
            ],
            [
                'name' => 'Frisco',
                'description' => 'Affordable pet supplies and accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Greenies',
                'description' => 'Dental care treats for dogs and cats',
                'is_active' => true,
            ],
            [
                'name' => 'Blue Buffalo',
                'description' => 'Natural pet food with real meat',
                'is_active' => true,
            ],
            [
                'name' => 'Wellness',
                'description' => 'Holistic pet nutrition and wellness',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}