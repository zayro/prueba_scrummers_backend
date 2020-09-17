<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $getToken = $request->get('token');
        $headerToken = $request->header('Token');
        $headerAuthorization = $request->header('Authorization');
        $headerBearer = $request->header('Bearer');

        if(isset($getToken) && !empty($getToken)){
            $token = $getToken;
        }
        if(isset($headerToken) && !empty($headerToken)){
            $token = $headerToken;
        }        
        if(isset($headerAuthorization) && !empty($headerAuthorization)){
            $token = $headerAuthorization;
        }        
        if(isset($headerBearer) && !empty($headerBearer)){
            $token = $headerBearer;
        }        
        
        if(!isset($token)) {
            
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);


        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.',
                'token' => $token,
                'request' => $request
            ], 400);
        }

        return $next(true);
    }
}