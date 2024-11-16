<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckTokenUserMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = Auth::guard('user-api')->user();
        }catch (TokenInvalidException $ex){
            return response()->json(['message' => 'Invalid token'], 401);
        }catch (TokenExpiredException $ex){
            return response()->json(['message' => 'Expired token'], 401);
        }
        if(!$user){
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return $next($request);
    }
}
