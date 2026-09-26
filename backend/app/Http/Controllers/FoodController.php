<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        $foods = $query->where('is_available', true)->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $foods,
        ], 200);
    }

    public function show($id)
    {
        $food = Food::with('restaurant')->find($id);

        if (!$food) {
            return response()->json([
                'status' => 'error',
                'message' => 'Food not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $food,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:1000',
            'category' => 'required|string',
            'image_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $restaurant = Restaurant::find($request->restaurant_id);

        if ($restaurant->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $food = Food::create([
            'restaurant_id' => $request->restaurant_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image_url' => $request->image_url,
            'is_available' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Food created successfully',
            'data' => $food,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'status' => 'error',
                'message' => 'Food not found',
            ], 404);
        }

        $restaurant = $food->restaurant;

        if ($restaurant->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|integer|min:1000',
            'category' => 'sometimes|string',
            'is_available' => 'sometimes|boolean',
            'image_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $food->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Food updated successfully',
            'data' => $food,
        ], 200);
    }
}
