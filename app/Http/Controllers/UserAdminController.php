<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAdminController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users_admin',
            'password' => 'required|string|min:8',
            'access_level' => 'required|integer',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validatedData->errors()], 400);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'access_level' => $request->access_level,
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validatedData->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email or password is incorrect'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update(['remember_token' => $token]);

        return response()->json(['token' => $token], 200);
    }


}
