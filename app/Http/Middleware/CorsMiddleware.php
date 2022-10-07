<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     * Configuracion del CORS
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $req, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            // 'Access-Control-Allow-Headers'     => 'Content-Type,Authorization,X-Requested-With,x-token',
            'Access-Control-Allow-Headers'     => '*',
            'Access-Control-Allow-Methods'     => 'POST,GET,OPTIONS,PUT,DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            // 'Access-Control-Max-Age'           => '86400',
        ];

        if ($req->isMethod('OPTIONS')) return response()->json(['method' => 'OPTIONS'], 200, $headers);

        $response = $next($req);

        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
