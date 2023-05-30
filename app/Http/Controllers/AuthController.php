<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Center;
use App\Models\UserToken;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {
            $user = new User();
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json(
                [
                    'entity' => 'users',
                    'action' => 'create',
                    'result' => 'success',
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'entity' => 'users',
                    'action' => 'create',
                    'result' => 'failed',
                ],
                409,
            );
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user->password !== $request->input('password')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //if (Hash::check($request->input('password'), $user2->password)) {
        //return response()->json(['message' => 'Unauthorized'], 401);
        //}
        //$credentials = $request->only(['email', 'password']);
        //if (!($token = Auth::attempt($credentials))) {
        //    return response()->json(['message' => 'Unauthorized'], 401);
        //}

        // get the authenticated user
        //$user = Auth::user();

        // build the token payload
        $payload = [
            'iss' => $request->fullUrl(), # token issuer
            'iat' => time(), # token issued at
            'exp' => time() + config('auth.guards.api.exp'), # token expiration
            'nbf' => time(), # token not before
            'jti' => uniqid('', true), # token id
            'sub' => $user->id, # token subject
            #'prv' => hash('sha256', $user->email), # token provider
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'lname' => $user->lname,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => Role::find($user->role_id)->role,
                'center_id' => $user->center_id,
                'center' => Center::find($user->center_id)->name,
                'created_at' => $user->created_at,
            ],
        ];

        // create the token
        //$token = JWT::encode($payload, env('JWT_SECRET'), 'HS256', null, $header);
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        // store the token into the db using model
        $userToken = new UserToken();
        $userToken->user_id = $user->id;
        $userToken->token = $token;
        $userToken->expires_at = DB::raw('FROM_UNIXTIME(' . $payload['exp'] . ')');
        $userToken->save();

        return $this->respondWithToken($token);
    }

    /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
}
