<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('is_open') && $request->is_open === 'true') {
            $query->where('is_open', true);
        }

        $restaurants = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $restaurants,
        ], 200);
    }

    public function show($id)
    {
        $restaurant = Restaurant::with('user')->find($id);

        if (!$restaurant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Restaurant not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $restaurant,
        ], 200);
    }

    public function foods($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Restaurant not found',
            ], 404);
        }

        $foods = $restaurant->foods()->where('is_available', true)->get();

        return response()->json([
            'status' => 'success',
            'data' => $foods,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'logo_url' => 'nullable|string',
            'cover_image_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $restaurant = Restaurant::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'logo_url' => $request->logo_url,
            'cover_image_url' => $request->cover_image_url,
            'is_open' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant created successfully',
            'data' => $restaurant,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Restaurant not found',
            ], 404);
        }

        if ($restaurant->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|string',
            'is_open' => 'sometimes|boolean',
            'logo_url' => 'nullable|string',
            'cover_image_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $restaurant->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant updated successfully',
            'data' => $restaurant,
        ], 200);
    }
}
