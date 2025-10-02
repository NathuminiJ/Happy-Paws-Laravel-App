<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $this->product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function it_can_create_feedback()
    {
        $feedbackData = [
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'product_id' => (string) $this->product->id,
            'product_name' => $this->product->name,
            'rating' => 5,
            'feedback_text' => 'Excellent product!',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        $feedback = Feedback::create($feedbackData);

        $this->assertInstanceOf(Feedback::class, $feedback);
        $this->assertEquals($this->user->id, $feedback->customer_id);
        $this->assertEquals(5, $feedback->rating);
        $this->assertEquals('Excellent product!', $feedback->feedback_text);
    }

    /** @test */
    public function it_can_find_feedback_by_id()
    {
        $feedbackData = [
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 4,
            'feedback_text' => 'Good product',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        $createdFeedback = Feedback::create($feedbackData);
        $foundFeedback = Feedback::find($createdFeedback->id);

        $this->assertInstanceOf(Feedback::class, $foundFeedback);
        $this->assertEquals($createdFeedback->id, $foundFeedback->id);
    }

    /** @test */
    public function it_can_update_feedback()
    {
        $feedbackData = [
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 3,
            'feedback_text' => 'Average product',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        $feedback = Feedback::create($feedbackData);
        
        $updateData = [
            'rating' => 5,
            'feedback_text' => 'Actually, excellent product!',
            'status' => 'read'
        ];

        $result = $feedback->update($updateData);

        $this->assertTrue($result);
        $this->assertEquals(5, $feedback->rating);
        $this->assertEquals('Actually, excellent product!', $feedback->feedback_text);
    }

    /** @test */
    public function it_can_delete_feedback()
    {
        $feedbackData = [
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 2,
            'feedback_text' => 'Poor product',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => false,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        $feedback = Feedback::create($feedbackData);
        $feedbackId = $feedback->id;

        $result = $feedback->delete();

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_get_feedback_for_customer()
    {
        // Create multiple feedback entries for the same customer
        Feedback::create([
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 5,
            'feedback_text' => 'Great product 1',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);

        Feedback::create([
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 4,
            'feedback_text' => 'Good service',
            'feedback_type' => 'service',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);

        $customerFeedbacks = Feedback::forCustomer($this->user->id, 10, 1);

        $this->assertObjectHasProperty('data', $customerFeedbacks);
        $this->assertCount(2, $customerFeedbacks->data);
    }

    /** @test */
    public function it_can_search_feedback()
    {
        Feedback::create([
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 5,
            'feedback_text' => 'Amazing product quality',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);

        $searchResults = Feedback::search('amazing', 10, 1);

        $this->assertObjectHasProperty('data', $searchResults);
    }

    /** @test */
    public function it_can_paginate_feedback()
    {
        // Create multiple feedback entries
        for ($i = 1; $i <= 15; $i++) {
            Feedback::create([
                'customer_id' => (string) $this->user->id,
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'rating' => rand(1, 5),
                'feedback_text' => "Feedback number $i",
                'feedback_type' => 'product',
                'status' => 'pending',
                'is_public' => true,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ]);
        }

        $paginatedFeedbacks = Feedback::paginate(10, 1);

        $this->assertObjectHasProperty('data', $paginatedFeedbacks);
        $this->assertObjectHasProperty('current_page', $paginatedFeedbacks);
        $this->assertObjectHasProperty('per_page', $paginatedFeedbacks);
        $this->assertObjectHasProperty('total', $paginatedFeedbacks);
    }

    /** @test */
    public function it_can_handle_different_feedback_types()
    {
        $types = ['product', 'service', 'delivery', 'general'];

        foreach ($types as $type) {
            $feedback = Feedback::create([
                'customer_id' => (string) $this->user->id,
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'rating' => 4,
                'feedback_text' => "Feedback for $type",
                'feedback_type' => $type,
                'status' => 'pending',
                'is_public' => true,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ]);

            $this->assertEquals($type, $feedback->feedback_type);
        }
    }

    /** @test */
    public function it_can_handle_public_and_private_feedback()
    {
        $publicFeedback = Feedback::create([
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 5,
            'feedback_text' => 'Public feedback',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);

        $privateFeedback = Feedback::create([
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 3,
            'feedback_text' => 'Private feedback',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => false,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);

        $this->assertTrue($publicFeedback->is_public);
        $this->assertFalse($privateFeedback->is_public);
    }

    /** @test */
    public function it_can_handle_admin_responses()
    {
        $feedback = Feedback::create([
            'customer_id' => (string) $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'rating' => 4,
            'feedback_text' => 'Customer feedback',
            'feedback_type' => 'product',
            'status' => 'pending',
            'is_public' => true,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);

        $adminResponse = 'Thank you for your feedback! We appreciate your input.';
        
        $feedback->update([
            'admin_response' => $adminResponse,
            'status' => 'responded',
            'admin_id' => 1
        ]);

        $this->assertEquals($adminResponse, $feedback->admin_response);
        $this->assertEquals('responded', $feedback->status);
    }
}
