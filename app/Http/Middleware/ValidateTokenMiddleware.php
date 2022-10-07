<?php

namespace App\Http\Middleware;

use App\Libraries\JWT\JWebToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            return response()->json(['error' => 'No hay TOKEN.',], Response::HTTP_UNAUTHORIZED);
        }

        // Validar si es un TOKEN válido
        try {
            $payload = JWebToken::getDecodedToken($jwt);
            $tokenExpirado = (strtotime('now') > $payload->exp);

            if ($tokenExpirado) return response()->json(['error' => 'El TOKEN ha expirado.',], Response::HTTP_UNAUTHORIZED);

            $request->merge(['idempleado' => $payload->uid, 'idusuario' => $payload->nick]);

            return $next($request);
        } catch (\Exception $e) {
            // Also tried JwtException
            Log::alert("Login\t{$request->ip()}\t{$e->getMessage()}");
            return response()->json(['error' => $e->getMessage(),], Response::HTTP_UNAUTHORIZED);
        }
    }
}
