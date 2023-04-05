<?php

namespace App\Http\Middleware;

use Closure;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\CoreException;
use Auth0\SDK\Exception\InvalidTokenException;
use Auth0\SDK\Auth0;

class Auth0Middleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json('No token provided', 401);
        }

        try {
            $config = new SdkConfiguration(strategy: SdkConfiguration::STRATEGY_API, domain: env('AUTH0_DOMAIN'), audience: [env('AUTH0_BASE_URL')]);

            $auth0 = new Auth0($config);
            $this->app->instance('auth0', $auth0);
            $token = $auth0->decode($token);
            $token->verify();
            //return response()->json('Verified token.', 200);
        } catch (InvalidTokenException $e) {
            // Handle token exception
            return response()->json('Invalid token', 401);
        } catch (CoreException $e) {
            // Handle other exceptions
            return response()->json('CoreException: ' . $e, 401);
        }
        //$this->validateToken($token);
        return $next($request);
    }

    public function validateToken($token)
    {
        //try {
        //    $configuration = new SdkConfiguration(strategy: SdkConfiguration::STRATEGY_API, domain: env('AUTH0_DOMAIN'), clientId: env('AUTH0_CLIENT_ID'), clientSecret: env('AUTH0_CLIENT_SECRET'), audience: [env('AUTH0_BASE_URL')]);
        //    $sdk = new Auth0($configuration);
        //    $token = $sdk->getBearerToken(get: ['token'], server: ['Authorization']);
        //} catch (InvalidTokenException $e) {
        //    throw $e;
        //}
    }
}
