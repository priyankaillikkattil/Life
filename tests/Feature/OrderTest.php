<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Laravel\Sanctum\Sanctum;

class OrderTest extends TestCase {
    use RefreshDatabase;

    public function test_customer_can_place_order() {
        $customer = User::factory()->create(['user_type' => 'customer']);
        Sanctum::actingAs($customer);

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->postJson('/api/orders', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id]);
    }

    public function test_customer_can_view_order_history() {
        $customer = User::factory()->create(['user_type' => 'customer']);
        Sanctum::actingAs($customer);

        Order::factory()->count(2)->create(['user_id' => $customer->id]);

        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }
}
