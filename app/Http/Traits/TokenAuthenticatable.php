<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

trait TokenAuthenticatable
{
    /**
     * Autentica o usuário com base no token Bearer enviado na requisição.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User|null
     */
    public function authenticateUserByToken(Request $request, $type)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $user = ($type == 0) ? User::where('remember_token', $token)->first() : Customer::where('remember_token', $token)->first();

        if (empty($user)) {
            return false;
        }

        return $user;
    }

}
