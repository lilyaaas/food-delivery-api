<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    // 1. List all open restaurants
    public function index()
    {
        $restaurants = Restaurant::where('is_open', true)->get(); //
        return response()->json($restaurants);
    }

    // 2. Create a new restaurant
    public function store(Request $request)
    {
        // Authorization: Only restaurant owners or admins can create restaurants
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'restaurant_owner') {
            return response()->json(['message' => 'Unauthorized. Only owners can create restaurants.'], 403);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'min_order_price' => 'numeric|min:0',
            'delivery_fee' => 'numeric|min:0',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',// max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('restaurants', 'public');
        }

        // Create Restaurant
        $restaurant = Restaurant::create([
            'owner_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'image' => $imagePath,
            'min_order_price' => $request->min_order_price ?? 0,
            'delivery_fee' => $request->delivery_fee ?? 0,
            'delivery_time' => $request->delivery_time,
        ]);

        return response()->json([
            'message' => 'Restaurant created successfully',
            'restaurant' => $restaurant
        ], 201);
    }

    // 3. View a specific restaurant
    public function show($id)
    {
        $restaurant = Restaurant::find($id);
        
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        return response()->json($restaurant);
    }
}