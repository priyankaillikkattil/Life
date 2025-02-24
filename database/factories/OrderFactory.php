<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory {
    
    protected $model = Order::class;

    public function definition() {
        return [
            'user_id' => User::factory(),
            'total_price' => $this->faker->randomFloat(2, 10, 500),
            'status' => 'pending', // Adjust based on your app
        ];
    }
}


