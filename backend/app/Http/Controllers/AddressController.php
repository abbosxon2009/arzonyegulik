<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->get();

        return response()->json([
            'status' => 'success',
            'data' => $addresses,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:100',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->is_default) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'street_address' => $request->street_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address created successfully',
            'data' => $address,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', auth()->id())->find($id);

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:100',
            'street_address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'postal_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->is_default) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'data' => $address,
        ], 200);
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', auth()->id())->find($id);

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully',
        ], 200);
    }
}
