<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function payWithClick(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::find($request->order_id);

        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => 'click',
            'status' => 'pending',
        ]);

        // Click API integration (placeholder)
        // In production, integrate with Click payment gateway
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment initiated with Click',
            'data' => [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'redirect_url' => 'https://click.uz/api/merchant/pay', // Placeholder
            ],
        ], 201);
    }

    public function payWithPayme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::find($request->order_id);

        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => 'payme',
            'status' => 'pending',
        ]);

        // Payme API integration (placeholder)
        // In production, integrate with Payme payment gateway
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment initiated with Payme',
            'data' => [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'redirect_url' => 'https://checkout.paycom.uz', // Placeholder
            ],
        ], 201);
    }

    public function callback(Request $request)
    {
        // Payment gateway callback handler
        // This would process callbacks from Click/Payme
        
        $paymentId = $request->input('payment_id');
        $status = $request->input('status');

        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found',
            ], 404);
        }

        if ($status === 'completed') {
            $payment->update(['status' => 'completed']);
            $payment->order->update(['status' => 'confirmed']);
        } else if ($status === 'failed') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback processed',
        ], 200);
    }

    public function show($id)
    {
        $payment = Payment::with('order')->find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found',
            ], 404);
        }

        if ($payment->order->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payment,
        ], 200);
    }
}
