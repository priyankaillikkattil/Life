<?php 

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

class ProductTest extends TestCase {

    use RefreshDatabase;

    public function setUp(): void {

        parent::setUp();
        $this->artisan('migrate'); 
        Category::factory()->count(2)->create(); 
        
    }

    public function test_product_creation() {
        $admin = User::factory()->create(['user_type' => 'admin']); 
    
        Sanctum::actingAs($admin, ['*']);
        $response = $this->postJson('/api/products', [
            'name'           => 'Test Product',
            'price'          => 100,
            'slug'           => 'test-product',
            'stock_quantity' => 10,
            'categories'     => Category::pluck('id')->toArray(),
        ]);    
        $response->assertStatus(201);
    }
}