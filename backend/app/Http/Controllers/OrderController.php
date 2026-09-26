<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id());

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->with('restaurant', 'items')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ], 200);
    }

    public function show($id)
    {
        $order = Order::with('restaurant', 'items.food', 'payment')
                      ->where('user_id', auth()->id())
                      ->find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'payment_method' => 'required|in:click,payme,cash',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $items = [];

            foreach ($request->items as $item) {
                $food = Food::find($item['food_id']);

                if (!$food || $food->restaurant_id != $request->restaurant_id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid food item',
                    ], 422);
                }

                $subtotal = $food->price * $item['quantity'];
                $totalAmount += $subtotal;

                $items[] = [
                    'food_id' => $food->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $food->price,
                    'subtotal' => $subtotal,
                ];
            }

            $deliveryFee = 8000;
            $totalAmount += $deliveryFee;

            $order = Order::create([
                'user_id' => auth()->id(),
                'restaurant_id' => $request->restaurant_id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'delivery_fee' => $deliveryFee,
                'payment_method' => $request->payment_method,
                'delivery_address' => $request->delivery_address,
                'notes' => $request->notes,
            ]);

            foreach ($items as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order->load('items'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Order creation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        $user = auth()->user();
        $restaurant = $order->restaurant;

        if ($user->id !== $restaurant->user_id && $user->id !== $order->user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,preparing,ready,on_the_way,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => $order,
        ], 200);
    }

    public function cancel($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot cancel this order',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Order cancelled successfully',
            'data' => $order,
        ], 200);
    }
}
