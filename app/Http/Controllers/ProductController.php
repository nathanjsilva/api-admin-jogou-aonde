<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\TokenAuthenticatable;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;


class ProductController extends Controller
{
    use TokenAuthenticatable;

    public function store(Request $request)
    {
        $user = $this->authenticateUserByToken($request, 0);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'type' => 'required|integer',
        ]);

        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'type' => $validatedData['type'],
        ]);

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    public function updateType(Request $request, $productId)
    {
        $user = $this->authenticateUserByToken($request, 0);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'type' => 'required|integer',
        ]);

        $product = Product::findOrFail($productId);
        $category = Category::findOrFail($validatedData['type']);

        $product->category()->associate($category);
        $product->save();

        return response()->json(['message' => 'Product type updated successfully'], 200);
    }

    public function getAll(Request $request)
    {
        $user = $this->authenticateUserByToken($request, 0);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $products = Product::with('category')->with('images')->get();

        return response()->json($products, 200);
    }



}
