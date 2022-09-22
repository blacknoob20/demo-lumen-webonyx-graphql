<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json([
        'version' => $router->app->version(),
        'app_name' => config('app.name'),
    ]);
});

// Generate APP_KEY
// $router->get('key', function () {
//     return response()->json([
//         'app_key' => \Illuminate\Support\Str::random(32),
//     ]);
// });

$router->post('graphql', 'GraphQLController@graphqlEndpoint');

$router->group(['middleware' => 'auth1'], function () use ($router) {
    $router->post('login', 'MyLoginController@iniciar_sesion');
});
