<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // Get user orders (My Orders)
    public function index(Request $request)
    {
        // Get user orders with pagination
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['restaurant', 'items.product'])
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    // Place a new Order
    public function store(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'address'       => 'required|string',
            'phone'         => 'required|string',
            'items'         => 'required|array', // list of items
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Transactional Order Creation
        try {
            return DB::transaction(function () use ($request) {
                
                $totalAmount = 0;
                $orderItemsData = []; // to store order items data
                
                // Get Restaurant details
                $restaurant = Restaurant::findOrFail($request->restaurant_id);

                // Loop through each item to calculate total
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    // Ensure product belongs to the restaurant
                    if ($product->restaurant_id != $request->restaurant_id) {
                         throw new \Exception("Product {$product->name} does not belong to this restaurant");
                    }

                    $price = $product->price;
                    $quantity = $item['quantity'];
                    
                    $totalAmount += ($price * $quantity);

                    // Prepare order item data
                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity'   => $quantity,
                        'price'      => $price,
                    ];
                }

                // Add delivery fee
                $totalAmount += $restaurant->delivery_fee;

                // Check minimum order price
                if ($totalAmount < $restaurant->min_order_price) {
                    throw new \Exception("Order total is less than the minimum order price of {$restaurant->min_order_price}");
                }

                // Create Order
                $order = Order::create([
                    'user_id'       => $request->user()->id,
                    'restaurant_id' => $request->restaurant_id,
                    'total_amount'  => $totalAmount,
                    'address'       => $request->address,
                    'phone'         => $request->phone,
                    'status'        => 'pending',
                ]);

                // Create Order Items
                foreach ($orderItemsData as $data) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $data['product_id'],
                        'quantity'   => $data['quantity'],
                        'price'      => $data['price'],
                    ]);
                }

                return response()->json([
                    'message' => 'Order placed successfully',
                    'order_id' => $order->id,
                    'total_amount' => $totalAmount
                ], 201);
            });

        // 3. Error Handling
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}