<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $carts = Cart::where('customer_id', $customer->id)->get();

        return response()->json($carts, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = $request->user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $existingCart = Cart::where('customer_id', $customer->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($existingCart) {
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            $cart = new Cart();
            $cart->customer_id = $customer->id;
            $cart->product_id = $product->id;
            $cart->quantity = $request->quantity;
            $cart->save();
        }

        return response()->json(['message' => 'Product added to cart successfully'], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = $request->user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = Cart::where('customer_id', $customer->id)
                    ->where('id', $id)
                    ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json(['message' => 'Cart item updated successfully'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $customer = $request->user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = Cart::where('customer_id', $customer->id)
                    ->where('id', $id)
                    ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Cart item deleted successfully'], 200);
    }

    public function clearCart(Request $request)
    {
        $customer = $request->user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Cart::where('customer_id', $customer->id)->delete();

        return response()->json(['message' => 'Cart cleared successfully'], 200);
    }
}