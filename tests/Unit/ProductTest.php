<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'is_active' => true
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(99.99, $product->price);
        $this->assertTrue($product->is_active);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::create([
            'name' => 'Original Product',
            'price' => 50.00,
            'status' => 'active'
        ]);

        $product->update([
            'name' => 'Updated Product',
            'price' => 75.00,
            'status' => 'inactive'
        ]);

        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals(75.00, $product->price);
        $this->assertEquals('inactive', $product->status);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::create([
            'name' => 'Product to Delete',
            'price' => 25.00
        ]);

        $productId = $product->id;
        $product->delete();

        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }

    /** @test */
    public function it_can_handle_image_upload()
    {
        $file = UploadedFile::fake()->image('product.jpg');

        $product = Product::create([
            'name' => 'Product with Image',
            'price' => 100.00,
            'image' => $file->store('products', 'public')
        ]);

        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    /** @test */
    public function it_can_get_active_products()
    {
        Product::create(['name' => 'Active Product', 'price' => 10.00, 'status' => 'active']);
        Product::create(['name' => 'Inactive Product', 'price' => 20.00, 'status' => 'inactive']);

        $activeProducts = Product::where('status', 'active')->get();

        $this->assertCount(1, $activeProducts);
        $this->assertEquals('Active Product', $activeProducts->first()->name);
    }

    /** @test */
    public function it_can_calculate_total_value()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 50.00,
            'stock' => 5
        ]);

        $totalValue = $product->price * $product->stock;
        $this->assertEquals(250.00, $totalValue);
    }

    /** @test */
    public function it_has_required_fillable_attributes()
    {
        $product = new Product();
        $fillable = $product->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('stock', $fillable);
    }

    /** @test */
    public function it_can_handle_low_stock()
    {
        $product = Product::create([
            'name' => 'Low Stock Product',
            'price' => 30.00,
            'stock' => 2
        ]);

        $isLowStock = $product->stock <= 5;
        $this->assertTrue($isLowStock);
    }

    /** @test */
    public function it_can_search_products_by_name()
    {
        Product::create(['name' => 'iPhone 13', 'price' => 999.00]);
        Product::create(['name' => 'Samsung Galaxy', 'price' => 899.00]);
        Product::create(['name' => 'MacBook Pro', 'price' => 1999.00]);

        $searchResults = Product::where('name', 'like', '%iPhone%')->get();
        $this->assertCount(1, $searchResults);
        $this->assertEquals('iPhone 13', $searchResults->first()->name);
    }

    /** @test */
    public function it_can_filter_products_by_price_range()
    {
        Product::create(['name' => 'Cheap Product', 'price' => 10.00]);
        Product::create(['name' => 'Expensive Product', 'price' => 1000.00]);
        Product::create(['name' => 'Mid Range Product', 'price' => 500.00]);

        $affordableProducts = Product::whereBetween('price', [0, 100])->get();
        $this->assertCount(1, $affordableProducts);
        $this->assertEquals('Cheap Product', $affordableProducts->first()->name);
    }
}
