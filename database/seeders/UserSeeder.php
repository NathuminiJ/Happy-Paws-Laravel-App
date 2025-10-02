<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@happypaws.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1-555-0123',
            'address' => '123 Admin Street',
            'city' => 'Admin City',
            'state' => 'AC',
            'zip_code' => '12345',
            'country' => 'US',
            'email_verified_at' => now(),
        ]);

        // Create sample customer users
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1-555-0124',
                'address' => '456 Customer Ave',
                'city' => 'Customer City',
                'state' => 'CC',
                'zip_code' => '54321',
                'country' => 'US',
                'date_of_birth' => '1990-05-15',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1-555-0125',
                'address' => '789 Pet Lover Blvd',
                'city' => 'Pet City',
                'state' => 'PC',
                'zip_code' => '67890',
                'country' => 'US',
                'date_of_birth' => '1985-08-22',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1-555-0126',
                'address' => '321 Animal Street',
                'city' => 'Animal Town',
                'state' => 'AT',
                'zip_code' => '13579',
                'country' => 'US',
                'date_of_birth' => '1992-12-10',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}