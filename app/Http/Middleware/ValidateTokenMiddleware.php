<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->header('X-TOKEN');
        // Validar si hay un TOKEN
        if (is_null($jwt) || $jwt == '') {
            Log::alert("Login\t{$request->ip()}\tNo hay TOKEN.");
            return response()->json(['error' => 'No hay TOKEN.',], 401);
        }

        // Validar si es un TOKEN válido
        try {
            JWT::decode($jwt, new Key(env('APP_KEY'), 'HS256'));
            return $next($request);
        } catch (\Exception $e) {
            // Also tried JwtException
            Log::alert("Login\t{$request->ip()}\t{$e->getMessage()}");
            return response()->json(['error' => $e->getMessage(),], 401);
        }
    }
}
