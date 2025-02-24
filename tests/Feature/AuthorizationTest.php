<?php 

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

class AuthorizationTest extends TestCase {
    use RefreshDatabase;

    public function test_customer_cannot_create_product() {
        $customer = User::factory()->create(['user_type' => 'customer']);
        Sanctum::actingAs($customer);

        $response = $this->postJson('/api/products', [
            'name' => 'Unauthorized Product',
            'price' => 100,
            'stock_quantity' => 10
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_product() {
        $admin = User::factory()->create(['user_type' => 'admin']);
        Sanctum::actingAs($admin, ['*']);

        $response = $this->postJson('/api/products', [
            'name'           => 'New Product',
            'price'          => 100,
            'slug'           => 'new-product',
            'stock_quantity' => 10,
            'categories'     => Category::factory()->count(1)->create()->pluck('id')->toArray(),
            'images'         => [] 
        ]);    
        $response->assertStatus(201);
    }
}
