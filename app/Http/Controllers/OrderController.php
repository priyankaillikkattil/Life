<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller {
    /** Create a New Order **/
    public function store(Request $request) {
        $rules  = [
            'items'                 => 'required|array',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
        ];
        $validator          = Validator::make($request->all(), $rules);    
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 422);
        } 
        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $itemsData  = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    return response()->json(['error' => 'Insufficient stock for ' . $product->name], 400);
                }

                // Reduce stock
                $product->decrement('stock_quantity', $item['quantity']);

                $totalPrice += $product->price * $item['quantity'];

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => Auth::id(),
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

            return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** View Order History (Authenticated Users) **/
    public function index() {
        try {
                $orders = Order::where('user_id', Auth::id())->with('items.product')->get();
                return response()->json($orders);
        } catch (\Exception $e) {
        
                return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Get Single Order Details    **/
    public function show($id) {
        try {
                $order = Order::where('user_id', Auth::id())->with('items.product')->findOrFail($id);
                return response()->json($order);
        }catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        } catch (\Exception $e) {            
                return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
