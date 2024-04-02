<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Http\Traits\TokenAuthenticatable;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    use TokenAuthenticatable;

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|string',
            'neighborhood' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'cpf_cnpj' => 'required|string|unique:customers,cpf_cnpj',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validatedData->errors()], 400);
        }
        
        $customer = new Customer();
        $customer->fill($request->all());
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json(['message' => 'Customer created successfully'], 201);
    }

    public function getAll(Request $request)
    {
        $user = $this->authenticateUserByToken($request, 1);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customers = Customer::all();
        return response()->json($customers, 200);
    }

    public function getById(Request $request, $id)
    {
        $user = $this->authenticateUserByToken($request, 1);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer, 200);
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

        $user = Customer::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email or password is incorrect'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update(['remember_token' => $token]);

        return response()->json(['token' => $token], 200);
    }
}
