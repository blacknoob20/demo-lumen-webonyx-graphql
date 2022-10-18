<?php

namespace App\Http\Middleware;

use App\Libraries\JWT\JWebToken;
use App\Libraries\Logger\Lg;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            $error = 'No hay TOKEN.';

            Lg::w("is_null($jwt) || $jwt == ''", Response::HTTP_UNAUTHORIZED, $error, get_class($this), __LINE__);
            return response()->json(['error' => $error,], Response::HTTP_UNAUTHORIZED);
        }

        // Validar si es un TOKEN válido
        try {
            $payload       = JWebToken::getDecodedToken($jwt);
            $ahora         = strtotime('now');
            $tokenExpirado = ($ahora > $payload->exp);

            if ($tokenExpirado) {
                $error = 'El TOKEN ha expirado.';

                Lg::w("$tokenExpirado = ($ahora > {$payload->exp})", Response::HTTP_UNAUTHORIZED, $error, get_class($this), __LINE__);
                return response()->json(['error' => $error,], Response::HTTP_UNAUTHORIZED);
            }

            $request->merge(['idempleado' => $payload->uid, 'idusuario' => $payload->nick]);

            return $next($request);
        } catch (\Exception $e) {
            // Also tried JwtException
            Lg::w("JWebToken::getDecodedToken('$jwt')", Response::HTTP_UNAUTHORIZED, $e->getMessage(), get_class($this), __LINE__);
            return response()->json(['error' => $e->getMessage(),], Response::HTTP_UNAUTHORIZED);
        }
    }
}
