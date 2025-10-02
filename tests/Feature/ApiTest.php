<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);
    }

    /** @test */
    public function it_can_register_a_new_user()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'customer'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'user' => ['id', 'name', 'email', 'role'],
                    'token'
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com'
        ]);
    }

    /** @test */
    public function it_can_login_user()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => ['id', 'name', 'email', 'role'],
                    'token'
                ]);
    }

    /** @test */
    public function it_can_get_products_without_authentication()
    {
        Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'status' => 'active'
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'price', 'status']
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_authenticated_user_profile()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ]);
    }

    /** @test */
    public function it_can_search_products()
    {
        Product::create([
            'name' => 'iPhone 13',
            'price' => 999.99,
            'status' => 'active'
        ]);

        Product::create([
            'name' => 'Samsung Galaxy',
            'price' => 899.99,
            'status' => 'active'
        ]);

        $response = $this->getJson('/api/products?search=iPhone');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_get_featured_products()
    {
        Product::create([
            'name' => 'Featured Product',
            'price' => 199.99,
            'status' => 'active',
            'is_featured' => true
        ]);

        Product::create([
            'name' => 'Regular Product',
            'price' => 99.99,
            'status' => 'active',
            'is_featured' => false
        ]);

        $response = $this->getJson('/api/products/featured');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_logout_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson(['message' => 'Logged out successfully']);
    }

    /** @test */
    public function it_requires_authentication_for_protected_routes()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_handle_invalid_login_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                ->assertJson(['message' => 'Invalid credentials']);
    }

    /** @test */
    public function it_can_validate_registration_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function it_can_get_product_by_id()
    {
        $product = Product::create([
            'name' => 'Specific Product',
            'price' => 299.99,
            'status' => 'active'
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $product->id,
                    'name' => 'Specific Product',
                    'price' => 299.99
                ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_product()
    {
        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404);
    }
}
