<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate()
    {
        //echo $app('auth0');
        $auth0 = app('auth0');
        $token = $auth0->login([
            'username' => 'your-username',
            'password' => 'your-password',
        ]);
        return response()->json($token, 200);
    }
}
