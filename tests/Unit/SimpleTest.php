<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_math()
    {
        $this->assertEquals(2, 1 + 1);
        $this->assertTrue(true);
        $this->assertFalse(false);
    }

    public function test_user_creation()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_database_connection()
    {
        $this->assertDatabaseCount('users', 0);
        
        User::create([
            'name' => 'Database Test',
            'email' => 'db@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['email' => 'db@example.com']);
    }

    public function test_user_roles()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $this->assertEquals('admin', $admin->role);
        $this->assertEquals('customer', $customer->role);
    }
}
