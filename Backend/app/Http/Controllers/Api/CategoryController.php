<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // 1. Pocket all the categories of a specific restaurant (Public)
    public function index($restaurant_id)
    {
        // Check if restaurant exists
        $restaurant = Restaurant::find($restaurant_id);
        
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        // Get categories
        $categories = Category::where('restaurant_id', $restaurant_id)->get();
        
        return response()->json($categories);
    }

    // 2. Create a new category for a restaurant (Protected)
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $restaurant = Restaurant::find($request->restaurant_id);

        if ($restaurant->owner_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized. You are not the owner of this restaurant.'], 403);
        }

        // Create
        $category = Category::create([
            'restaurant_id' => $request->restaurant_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }
}