<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // 1. List all products of a specific restaurant (Public)
    public function index($restaurant_id)
    {
        //
        $products = Product::where('restaurant_id', $restaurant_id)
                            ->with('category') 
                            ->get();

        return response()->json($products);
    }

    // 2. Create a new product for a restaurant (Protected)
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Authorization: Check if the user is the owner of the restaurant
        $restaurant = Restaurant::find($request->restaurant_id);
        if ($restaurant->owner_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if the category belongs to the restaurant
        $category = Category::find($request->category_id);
        if ($category->restaurant_id != $request->restaurant_id) {
            return response()->json(['message' => 'This category does not belong to this restaurant'], 422);
        }

        // Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create Product
        $product = Product::create([
            'restaurant_id' => $request->restaurant_id,
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'description'   => $request->description,
            'price'         => $request->price,
            'image'         => $imagePath,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    // View a specific product
    public function show($id)
    {
        $product = Product::with(['restaurant', 'category'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}