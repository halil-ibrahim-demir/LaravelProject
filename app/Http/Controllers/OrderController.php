<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cartItems = $user->carts()->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'zipcode' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->price * $item->quantity;
        }

        $order = DB::transaction(function () use ($user, $data, $totalAmount, $cartItems) {
            $order = Order::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address1' => $data['address1'],
                'address2' => $data['address2'] ?? null,
                'city' => $data['city'],
                'state' => $data['state'] ?? null,
                'country' => $data['country'],
                'zipcode' => $data['zipcode'],
                'total_amount' => $totalAmount,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'tracking_no' => 'TRK-' . strtoupper(Str::random(10)),
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            }

            $user->carts()->delete();

            return $order;
        });

        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
    }
}
