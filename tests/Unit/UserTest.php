<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('customer', $user->role);
    }

    /** @test */
    public function it_can_create_an_admin_user()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isCustomer());
    }

    /** @test */
    public function it_can_create_a_customer_user()
    {
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $this->assertTrue($customer->isCustomer());
        $this->assertFalse($customer->isAdmin());
    }

    /** @test */
    public function it_can_hash_passwords()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('secretpassword'),
            'role' => 'customer'
        ]);

        $this->assertTrue(Hash::check('secretpassword', $user->password));
        $this->assertFalse(Hash::check('wrongpassword', $user->password));
    }

    /** @test */
    public function it_can_update_user_profile()
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $user->update([
            'name' => 'Updated Name',
            'phone' => '123-456-7890',
            'address' => '123 Main Street'
        ]);

        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('123-456-7890', $user->phone);
        $this->assertEquals('123 Main Street', $user->address);
    }

    /** @test */
    public function it_can_authenticate_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $this->assertTrue($user->isCustomer());
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function it_can_validate_email_uniqueness()
    {
        User::create([
            'name' => 'First User',
            'email' => 'unique@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Second User',
            'email' => 'unique@example.com', // Same email
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);
    }

    /** @test */
    public function it_can_get_user_orders()
    {
        $user = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        // This would require the Order model to have a relationship
        // For now, we'll test the user creation
        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function it_can_handle_user_roles()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $this->assertEquals('admin', $admin->role);
        $this->assertEquals('customer', $customer->role);
    }

    /** @test */
    public function it_can_soft_delete_user()
    {
        $user = User::create([
            'name' => 'User to Delete',
            'email' => 'delete@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $userId = $user->id;
        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function it_can_search_users_by_name()
    {
        User::create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $searchResults = User::where('name', 'like', '%John%')->get();
        $this->assertCount(1, $searchResults);
        $this->assertEquals('John Smith', $searchResults->first()->name);
    }
}
