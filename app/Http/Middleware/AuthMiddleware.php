<?php

namespace App\Http\Middleware;

use App\Libraries\Logger\Lg;
use Closure;
use Illuminate\Http\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $credencials = validator($request->all(), [
            'idusuario' => 'required|max:30',
            'clave'     => 'required|max:100',
        ]);

        if ($credencials->fails()) {
            Lg::w(json_encode($request->all()), Response::HTTP_UNAUTHORIZED, json_encode($credencials->errors()), get_class($this), __LINE__, 'notice');
            return response()->json(['login' => false, 'error' => 'La petición no cumple con los requisitos de autenticación.',], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
