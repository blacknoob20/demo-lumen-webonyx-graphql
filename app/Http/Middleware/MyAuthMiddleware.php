<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class MyAuthMiddleware
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
        $credencials = validator($request->all(),[
            'idusuario' => 'required|max:15',
            'clave'     => 'required|max:100',
            // 'ip'        => 'required|ip',
        ]);

        if ($credencials->fails()) {
            Log::alert("Login\t{$request->server('REMOTE_ADDR')}\t".json_encode($credencials->errors()));
            return response()->json(
                [
                    'error' => 'Usuario o Contraseña incorrectos.',
                ],
                401
            );
        }

        return $next($request);
    }
}
