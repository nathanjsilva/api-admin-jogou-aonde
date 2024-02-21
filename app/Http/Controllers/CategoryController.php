<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\TokenAuthenticatable;
use App\Models\Category;


class CategoryController extends Controller
{
    use TokenAuthenticatable;

    public function index()
    {
        $user = $this->authenticateUserByToken($request);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $user = $this->authenticateUserByToken($request);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->authenticateUserByToken($request);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return response()->json($category);
    }

    public function destroy($id)
    {
        $user = $this->authenticateUserByToken($request);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }
}
