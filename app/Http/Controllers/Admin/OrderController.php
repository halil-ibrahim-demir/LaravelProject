<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get();
        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|string|in:pending,accepted,shipping,completed,canceled',
            'tracking_no' => 'nullable|string|max:255',
        ]);

        $order->update($data);

        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }
}
