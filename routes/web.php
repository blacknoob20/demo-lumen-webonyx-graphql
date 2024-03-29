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

// ********************
// ** Rutas PUBLICAS **
// ********************
$router->get('/', function () use ($router) {
    return response()->json([
        'framework' => $router->app->version(),
        'app_name' => config('app.name'),
    ]);
});

// Generate APP_KEY
// $router->get('key', function () {
//     return response()->json([
//         'app_key' => \Illuminate\Support\Str::random(32),
//     ]);
// });
// Generate CAPTCHA image
$router->get('captcha', 'CaptchaController@generarCaptcha');

// Ruta para el API de login
$router->group(['middleware' => 'auth1'], function () use ($router) {
    $router->post('login', 'LoginController@iniciar_sesion');
});
$router->group(['prefix' => 'verificacion'], function () use ($router) {
    $router->post('/', 'LoginController@getPregunta');
    $router->post('preguntas', 'LoginController@validaPregunta');
});

// Beneficiarios de Desarrollo Comunitario
$router->group(['prefix' => 'beneficiario'], function () use ($router) {
    $router->post('pagineo', 'Ds\BeneficiarioController@BeneficiariosPag');
    $router->post('datos', 'Ds\BeneficiarioController@BeneficiarioDatos');
    $router->post('servicios', 'Ds\BeneficiarioController@BeneficiarioServicios');
    $router->post('preguntas', 'Ds\BeneficiarioController@BeneficiarioPreguntas');
});

// ********************
// ** Rutas PRIVADAS **
// ********************
// Solo se puede acceder con TOKEN
$router->group(['middleware' => 'token'], function () use ($router) {
    $router->get('renovar', 'LoginController@renovarToken');
    $router->post('graphql', 'GraphQLController@graphqlEndpoint');
});
