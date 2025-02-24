<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory {
    protected $model = Product::class;

    public function definition() {
        return [
            'name'           => $this->faker->word,
            'price'          => $this->faker->randomFloat(2, 10, 500),
            'slug'           => $this->faker->slug,
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'images'         => json_encode([]),
        ];
    }
}
