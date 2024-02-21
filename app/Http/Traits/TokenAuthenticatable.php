<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Http\Request;

trait TokenAuthenticatable
{
    /**
     * Autentica o usuário com base no token Bearer enviado na requisição.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User|null
     */
    public function authenticateUserByToken(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $user = User::where('remember_token', $token)->first();

        if (empty($user)) {
            return false;
        }

        return true;
    }
}
