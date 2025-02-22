<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;


class OrderSeeder extends Seeder
{
    public function run()
    {
        
        $users = User::all();
        
        foreach ($users as $user) {
            
            DB::beginTransaction();
            try {
                $totalPrice = 0;
                $itemsData = [];
                
                
                $productIds = Product::inRandomOrder()->take(3)->pluck('id');
                
                foreach ($productIds as $productId) {
                    $product = Product::find($productId);
                    $quantity = rand(1, 5); 
                    
                   
                    if ($product->stock_quantity >= $quantity) {
                        
                        $product->decrement('stock_quantity', $quantity);
                        
                      
                        $itemsData[] = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price' => $product->price,
                        ];
                        
                        
                        $totalPrice += $product->price * $quantity;
                    } else {
                        
                        continue;
                    }
                }

                
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);
                
              
                foreach ($itemsData as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
               
                continue; 
            }
        }
    }
}
