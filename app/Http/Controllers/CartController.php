<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Customer;
use App\Http\Traits\TokenAuthenticatable;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use TokenAuthenticatable;

    public function index(Request $request)
    {
        $customer = $this->authenticateUserByToken($request, 1);

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $carts = Cart::with('customer', 'product')->where('customer_id', $customer->id)->get();

        return response()->json($carts, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $customer = $this->authenticateUserByToken($request, 1);

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $existingCart = Cart::where('customer_id', $customer->id)
                                ->where('product_id', $product->id)
                                ->first();

            if ($existingCart) {
                $existingCart->quantity += $productData['quantity'];
                $existingCart->save();
            } else {
                $cart = new Cart();
                $cart->customer_id = $customer->id;
                $cart->product_id = $product->id;
                $cart->quantity = $productData['quantity'];
                $cart->save();
            }
        }

        return response()->json(['message' => 'Products added to cart successfully'], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = $this->authenticateUserByToken($request, 1);

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
        $customer = $this->authenticateUserByToken($request, 1);

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
        $customer = $this->authenticateUserByToken($request, 1);
        
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Cart::where('customer_id', $customer->id)->delete();

        return response()->json(['message' => 'Cart cleared successfully'], 200);
    }
}
