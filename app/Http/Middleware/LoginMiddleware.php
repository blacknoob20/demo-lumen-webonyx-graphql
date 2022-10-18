<?php

namespace App\Http\Middleware;

use App\Libraries\Logger\Lg;
use Closure;
use Illuminate\Http\Response;

class LoginMiddleware
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
            'idusuario' => 'required|max:15',
            'clave'     => 'required|max:100',
        ]);

        if ($credencials->fails()) {
            Lg::w(json_encode($request->all()), Response::HTTP_UNAUTHORIZED, json_encode($credencials->errors()), get_class($this), __LINE__, 'notice');

            return response()->json(['error' => 'Usuario o Contraseña incorrectos.',], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
