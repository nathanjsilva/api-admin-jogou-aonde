<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\TokenAuthenticatable;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use TokenAuthenticatable;

    public function purchase(Request $request)
    {
        $customer = $this->authenticateUserByToken($request, 1);

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = Cart::with('product')->where('customer_id', $customer->id)->get();

        $totalQuantity = 0;
        $totalAmount = 0;
        $cartDetails = [];

        foreach ($cart as $item) {
            $productTotal = $item->product->price * $item->quantity;
            $totalQuantity += $item->quantity;
            $totalAmount += $productTotal;

            $cartDetails[] = [
                'product_name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'total_per_product' => $productTotal
            ];
        }

        return response()->json([
            'customer_id' => $customer->id,
            'total_quantity' => $totalQuantity,
            'total_amount' => $totalAmount,
            'cart_details' => $cartDetails
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'total_amount' => 'required',
            'payment_method' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 400);
        }

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->total_amount = $request->total_amount;
        $order->payment_method = $request->payment_method;
        $order->payment_status = 1;
        $order->save();

        return response()->json(['message' => 'Order created successfully'], 201);
    }

}
