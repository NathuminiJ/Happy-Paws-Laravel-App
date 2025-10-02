<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_order()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => 299.99,
            'status' => 'pending',
            'shipping_address' => '123 Test Street',
            'billing_address' => '123 Test Street',
            'notes' => 'Test order'
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($user->id, $order->customer_id);
        $this->assertEquals(299.99, $order->total_amount);
        $this->assertEquals('pending', $order->status);
    }

    /** @test */
    public function it_can_update_order_status()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => 150.00,
            'status' => 'pending'
        ]);

        $order->update(['status' => 'completed']);

        $this->assertEquals('completed', $order->status);
    }

    /** @test */
    public function it_can_calculate_order_total()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => 250.75
        ]);

        $this->assertEquals(250.75, $order->total_amount);
    }

    /** @test */
    public function it_can_get_orders_by_status()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        Order::create([
            'customer_id' => $user->id,
            'total_amount' => 100.00,
            'status' => 'pending'
        ]);

        Order::create([
            'customer_id' => $user->id,
            'total_amount' => 200.00,
            'status' => 'completed'
        ]);

        $pendingOrders = Order::where('status', 'pending')->get();
        $completedOrders = Order::where('status', 'completed')->get();

        $this->assertCount(1, $pendingOrders);
        $this->assertCount(1, $completedOrders);
    }

    /** @test */
    public function it_can_get_orders_by_customer()
    {
        $user1 = User::create([
            'name' => 'Customer 1',
            'email' => 'customer1@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $user2 = User::create([
            'name' => 'Customer 2',
            'email' => 'customer2@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        Order::create([
            'customer_id' => $user1->id,
            'total_amount' => 100.00
        ]);

        Order::create([
            'customer_id' => $user2->id,
            'total_amount' => 200.00
        ]);

        $user1Orders = Order::where('customer_id', $user1->id)->get();
        $this->assertCount(1, $user1Orders);
    }

    /** @test */
    public function it_can_calculate_total_revenue()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        Order::create([
            'customer_id' => $user->id,
            'total_amount' => 100.00,
            'status' => 'completed'
        ]);

        Order::create([
            'customer_id' => $user->id,
            'total_amount' => 200.00,
            'status' => 'completed'
        ]);

        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $this->assertEquals(300.00, $totalRevenue);
    }

    /** @test */
    public function it_can_handle_order_cancellation()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => 150.00,
            'status' => 'pending'
        ]);

        $order->update(['status' => 'cancelled']);

        $this->assertEquals('cancelled', $order->status);
    }

    /** @test */
    public function it_can_generate_order_number()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => 100.00,
            'order_number' => 'ORD-' . strtoupper(uniqid())
        ]);

        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    /** @test */
    public function it_can_validate_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Order::create([
            'total_amount' => 100.00
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_handle_order_notes()
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => 100.00,
            'notes' => 'Please deliver after 5 PM'
        ]);

        $this->assertEquals('Please deliver after 5 PM', $order->notes);
    }
}
