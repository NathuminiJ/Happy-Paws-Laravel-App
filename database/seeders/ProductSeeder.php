<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $brands = Brand::all();

        $products = [
            [
                'name' => 'Premium Dog Food - Chicken & Rice',
                'description' => 'High-quality dry dog food made with real chicken and brown rice. Perfect for adult dogs of all sizes.',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock_quantity' => 50,
                'brand_id' => $brands->where('name', 'Royal Canin')->first()->id,
                'is_featured' => true,
                'attributes' => ['size' => '15lb', 'age' => 'Adult', 'protein_source' => 'Chicken'],
            ],
            [
                'name' => 'Interactive Cat Toy - Feather Wand',
                'description' => 'Engaging feather wand toy that will keep your cat entertained for hours. Great for exercise and bonding.',
                'price' => 12.99,
                'stock_quantity' => 30,
                'brand_id' => $brands->where('name', 'Kong')->first()->id,
                'is_featured' => true,
                'attributes' => ['type' => 'Interactive', 'material' => 'Feather', 'age' => 'All'],
            ],
            [
                'name' => 'Comfortable Pet Bed - Large',
                'description' => 'Ultra-soft, machine-washable pet bed with orthopedic support. Perfect for senior pets.',
                'price' => 49.99,
                'sale_price' => 39.99,
                'stock_quantity' => 25,
                'brand_id' => $brands->where('name', 'Frisco')->first()->id,
                'is_featured' => true,
                'attributes' => ['size' => 'Large', 'material' => 'Memory Foam', 'washable' => 'Yes'],
            ],
            [
                'name' => 'Dental Care Treats - Greenies',
                'description' => 'Veterinarian recommended dental treats that help clean teeth and freshen breath.',
                'price' => 19.99,
                'stock_quantity' => 40,
                'brand_id' => $brands->where('name', 'Greenies')->first()->id,
                'attributes' => ['type' => 'Dental', 'flavor' => 'Original', 'size' => 'Regular'],
            ],
            [
                'name' => 'Grain-Free Cat Food - Salmon',
                'description' => 'Natural, grain-free cat food with real salmon. Perfect for cats with food sensitivities.',
                'price' => 34.99,
                'stock_quantity' => 35,
                'brand_id' => $brands->where('name', 'Blue Buffalo')->first()->id,
                'is_featured' => true,
                'attributes' => ['type' => 'Grain-Free', 'protein' => 'Salmon', 'size' => '12lb'],
            ],
            [
                'name' => 'Dog Training Treats - Chicken',
                'description' => 'Small, soft training treats perfect for positive reinforcement during training sessions.',
                'price' => 8.99,
                'stock_quantity' => 60,
                'brand_id' => $brands->where('name', 'Purina Pro Plan')->first()->id,
                'attributes' => ['type' => 'Training', 'flavor' => 'Chicken', 'size' => 'Small'],
            ],
            [
                'name' => 'Cat Scratching Post - Sisal',
                'description' => 'Tall scratching post with sisal rope covering. Helps protect furniture and provides exercise.',
                'price' => 39.99,
                'stock_quantity' => 20,
                'brand_id' => $brands->where('name', 'Frisco')->first()->id,
                'attributes' => ['height' => '32 inches', 'material' => 'Sisal', 'base' => 'Stable'],
            ],
            [
                'name' => 'Dog Leash - Retractable',
                'description' => 'Durable retractable leash with 16-foot extension. Perfect for walks and training.',
                'price' => 24.99,
                'stock_quantity' => 45,
                'brand_id' => $brands->where('name', 'Kong')->first()->id,
                'attributes' => ['length' => '16ft', 'weight_limit' => '110lbs', 'material' => 'Nylon'],
            ],
            [
                'name' => 'Cat Litter - Clumping',
                'description' => 'Premium clumping cat litter with odor control. Easy to clean and long-lasting.',
                'price' => 15.99,
                'stock_quantity' => 80,
                'brand_id' => $brands->where('name', 'Frisco')->first()->id,
                'attributes' => ['type' => 'Clumping', 'scent' => 'Unscented', 'weight' => '20lb'],
            ],
            [
                'name' => 'Dog Shampoo - Oatmeal',
                'description' => 'Gentle oatmeal shampoo for dogs with sensitive skin. Hypoallergenic and tear-free formula.',
                'price' => 11.99,
                'stock_quantity' => 55,
                'brand_id' => $brands->where('name', 'Wellness')->first()->id,
                'attributes' => ['type' => 'Oatmeal', 'size' => '16oz', 'scent' => 'Mild'],
            ],
            [
                'name' => 'Cat Food Bowl Set - Ceramic',
                'description' => 'Set of two ceramic food bowls with non-slip base. Dishwasher safe and easy to clean.',
                'price' => 16.99,
                'stock_quantity' => 30,
                'brand_id' => $brands->where('name', 'Frisco')->first()->id,
                'attributes' => ['material' => 'Ceramic', 'pieces' => '2', 'size' => 'Medium'],
            ],
            [
                'name' => 'Dog Collar - Reflective',
                'description' => 'Durable nylon collar with reflective strip for safety during night walks.',
                'price' => 9.99,
                'stock_quantity' => 70,
                'brand_id' => $brands->where('name', 'Kong')->first()->id,
                'attributes' => ['material' => 'Nylon', 'reflective' => 'Yes', 'adjustable' => 'Yes'],
            ],
        ];

        foreach ($products as $product) {
            $product['admin_id'] = $admin->id;
            $product['image'] = 'https://via.placeholder.com/400x300/4F46E5/FFFFFF?text=' . urlencode($product['name']);
            $product['slug'] = \Illuminate\Support\Str::slug($product['name']);
            $product['sku'] = 'HP-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            Product::create($product);
        }
    }
}