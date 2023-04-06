<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function respondWithToken($token)
    {
        $payload = Auth::payload();
        $expirationTime = $payload->get('exp');
        return response()->json(
            [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expirationTime,
            ],
            200,
        );
    }
}
