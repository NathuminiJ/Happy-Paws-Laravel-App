<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create customer user
        $this->customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);
    }

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function customer_cannot_access_admin_dashboard()
    {
        $this->actingAs($this->customer);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_products()
    {
        Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'status' => 'active'
        ]);

        $this->actingAs($this->admin);

        $response = $this->get('/admin/products');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_product()
    {
        $this->actingAs($this->admin);

        $productData = [
            'name' => 'New Product',
            'description' => 'Product Description',
            'price' => 149.99,
            'stock' => 10,
            'status' => 'active',
            'category' => 'Electronics',
            'brand' => 'Test Brand'
        ];

        $response = $this->post('/admin/products', $productData);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    /** @test */
    public function admin_can_update_product()
    {
        $product = Product::create([
            'name' => 'Original Product',
            'price' => 100.00,
            'status' => 'active'
        ]);

        $this->actingAs($this->admin);

        $response = $this->put("/admin/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 150.00,
            'status' => 'inactive'
        ]);

        $response->assertRedirect('/admin/products');
        
        $product->refresh();
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals(150.00, $product->price);
    }

    /** @test */
    public function admin_can_delete_product()
    {
        $product = Product::create([
            'name' => 'Product to Delete',
            'price' => 50.00
        ]);

        $this->actingAs($this->admin);

        $response = $this->delete("/admin/products/{$product->id}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function admin_can_view_orders()
    {
        Order::create([
            'customer_id' => $this->customer->id,
            'total_amount' => 299.99,
            'status' => 'pending'
        ]);

        $this->actingAs($this->admin);

        $response = $this->get('/admin/orders');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_customers()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/customers');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_order_status()
    {
        $order = Order::create([
            'customer_id' => $this->customer->id,
            'total_amount' => 199.99,
            'status' => 'pending'
        ]);

        $this->actingAs($this->admin);

        $response = $this->put("/admin/orders/{$order->id}", [
            'status' => 'completed'
        ]);

        $response->assertRedirect('/admin/orders');
        
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    /** @test */
    public function admin_can_view_customer_feedback()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/feedback');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_admin_login_page()
    {
        $response = $this->get('/admin-login');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_login_via_admin_login()
    {
        $response = $this->post('/admin-login', [
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    /** @test */
    public function customer_cannot_login_via_admin_login()
    {
        $response = $this->post('/admin-login', [
            'email' => 'customer@example.com',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function admin_can_logout()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin-logout');

        $response->assertRedirect('/admin-login');
    }

    /** @test */
    public function admin_dashboard_shows_analytics()
    {
        // Create some test data
        Product::create(['name' => 'Product 1', 'price' => 100.00]);
        Order::create([
            'customer_id' => $this->customer->id,
            'total_amount' => 200.00,
            'status' => 'completed'
        ]);

        $this->actingAs($this->admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200)
                ->assertSee('Dashboard')
                ->assertSee('Products')
                ->assertSee('Orders');
    }
}
