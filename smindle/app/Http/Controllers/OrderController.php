<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\BasketItem;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'basket' => ['required', 'array'],
            'basket.*.name' => ['required', 'string', 'max:200'],
            'basket.*.type' => ['required', 'string', 'regex:/(unit|subscription)/i'],
            'basket.*.price' => ['required', 'integer', 'min:1']
        ]);

        $order = Order::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'address' => $validated['address'],
        ]);

        foreach ($validated['basket'] as $item) {
            $order->basket()->create([
                'name' => $item['name'],
                'type' => $item['type'],
                'price' => $item['price'],
            ]);
        }

        $newOrder = $order->with('basket')->where('id', $order->id)->get()->toArray();

        Log::debug('Order created', ['order_id' => $order->id]);

        $hasSubscription = collect($validated['basket'])->contains('type', 'subscription');
        if ($hasSubscription) {
            Log::debug('Send async request to very slow API');

            dispatch(function () use ($order) {
                try {
                    $response = Http::post('https://fakeresponder.com/?sleep=5000', [
                        'product_name' => 'Product_name',
                        'price' => 100.50,
                        'timestamp' => now()->timestamp,
                    ]);

                    Log::debug('Get responce from very slow API', ['response' => $response]);

                    if ($response->failed()) {
                        Log::error('Failed to send order to very slow API', ['order_id' => $order->id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception occurred while sending order to very slow API', ['order_id' => $order->id, 'message' => $e->getMessage()]);
                }
            });
        }

        return response()->json([
            'message' => 'Order created',
            'order' => $newOrder
        ], 201);
    }
}
