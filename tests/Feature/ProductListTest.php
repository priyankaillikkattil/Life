<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
class ProductListTest extends TestCase {
    use RefreshDatabase;

    public function test_product_listing() {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    
        Product::factory()->count(5)->create();
    
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data'); // Assuming pagination
    }
    

    public function test_filter_products_by_category() {
        $user = User::factory()->create(); 
        Sanctum::actingAs($user); 
    
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $product->categories()->attach($category->id);
    
        $response = $this->getJson('/api/products?category_id=' . $category->id);
        $response->assertStatus(200);
    }
    
}
