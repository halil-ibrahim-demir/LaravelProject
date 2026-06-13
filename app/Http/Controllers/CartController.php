<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        return view('front.cart', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $userId = auth()->id();

        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        $currentQuantity = $cart ? $cart->quantity : 0;
        if ($product->stock < ($currentQuantity + $quantity)) {
            return redirect()->back()->with('error', 'Yetersiz stok miktarı.');
        }

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Ürün sepete eklendi.');
    }

    public function destroy($id)
    {
        $cart = auth()->user()->carts()->findOrFail($id);
        $cart->delete();

        return redirect()->back()->with('success', 'Ürün sepetten çıkarıldı.');
    }
}
