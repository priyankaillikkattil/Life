<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase {
    use RefreshDatabase;

    public function test_product_creation() {
        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 100,
            'slug' => 'test-product',
            'stock_quantity' => 10,
            'categories' => [1, 2],
        ]);
        $response->assertStatus(201);
    }
}