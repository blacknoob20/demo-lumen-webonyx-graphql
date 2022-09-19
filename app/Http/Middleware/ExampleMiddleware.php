<?php

namespace App\Http\Middleware;

use Closure;

class ExampleMiddleware
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

        // echo '<pre>';
        // // var_dump($request);
        // var_dump($request->server('REQUEST_URI'));
        // // var_dump(request()->headers->get('referer'));
        // echo '</pre>';

        if ($request->server('REQUEST_URI') == '/public/key'){ return response()->json(['error'=>'No autorizado'], 401);}
        else {return $next($request);}
    }
}
