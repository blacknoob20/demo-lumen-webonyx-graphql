<?php

namespace App\Http\Controllers;

use App\Libraries\JWT\JWebToken;
use App\Models\Oracle\SE\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Controlador para iniciar sesion
     * @param Request $req
     *
     * @return Usuario
     */
    public function iniciar_sesion(Request $req)
    {
        $UsuarioProps = [
            ...$req->all(),
            'idusuario_log' => $req->input('idusuario'),
            'ip' => $req->ip(),
            // * Determinar si es un usuario de red
            'idusuariored' => (strstr($req->input('idusuario'), '.')
                ? $req->input('idusuario')
                : NULL
            ),
        ];

        $Usuario = new Usuario($UsuarioProps);
        $datos   = $Usuario->validaUsuario();

        return response()->json($datos, Response::HTTP_OK);
    }

    /**
     * Controlador para renovar el TOKEN
     * @param Request $req
     *
     * @return JWT
     */
    public function renovarToken(Request $req)
    {
        $payload = ['uid' => $req->input('idempleado'), 'nick' => $req->input('idusuario')];
        $newToken = ['token' => JWebToken::getToken($payload), 'idusuario' => $req->input('idusuario')];

        return response()->json($newToken, Response::HTTP_OK);
    }

    /**
     * Controlador para obtener las preguntas del usuario
     * @param Request $req
     *
     * @return Usuario
     */
    public function getPregunta(Request $req)
    {
        $credencials = validator($req->all(), [
            'idusuario'    => 'required|max:15',
            'idempleado'   => 'required|numeric|digits_between:1,8',
            'numdocumento' => 'required|numeric|digits:10',
        ]);

        if ($credencials->fails()) {
            Log::alert("Login\t{$req->ip()}\t" . json_encode($credencials->errors()));
            return response()->json(
                ['error' => 'Las credenciales del usuario son incorrectas.',],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        $Usuario      = new Usuario([...$req->all()]);
        $pregunta     = $Usuario->getPregunta();

        return response()->json($pregunta, Response::HTTP_OK);
    }

    /**
     * Controlador para validar la respuesta del usuario
     * @param Request $req
     *
     * @return Usuario
     */
    public function validaPregunta(Request $req)
    {
        $credencials = validator($req->all(), [
            'idusuario'      => 'required|max:15',
            'idempleado'     => 'required|numeric|digits_between:1,8',
            'numdocumento'   => 'required|numeric|digits:10',
            'numeropregunta' => 'required|numeric',
            'respuesta'      => 'required',
        ]);

        if ($credencials->fails()) {
            Log::alert("Login\t{$req->ip()}\t" . json_encode($credencials->errors()));
            return response()->json(
                ['error' => 'Debe ingresar la respuesta.',],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $Usuario  = new Usuario([...$req->all()]);
        $pregunta = $Usuario->validaPregunta();

        if ($pregunta['codigovalidacion'] > 0) return response()->json($pregunta);
        else {
            Log::alert("Login\t{$req->ip()}\t" . json_encode($pregunta));
            return response()->json(
                ['error' => $pregunta['descripcionvalidacion'],],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
