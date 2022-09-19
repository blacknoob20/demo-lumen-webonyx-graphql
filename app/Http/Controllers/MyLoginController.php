<?php

namespace App\Http\Controllers;

use App\Models\Oracle\SE\Usuario;
use Illuminate\Http\Request;

class MyLoginController extends Controller
{
    public $idusuario;
    public $clave;
    public $ip;
    /**
     * Controlador para iniciar sesion
     *
     * @return void
     */
    public function iniciar_sesion(Request $request)
    {
        $this->idusuario = $request->input('idusuario');
        $this->clave     = $request->input('clave');
        // $this->ip        = $request->input('ip');
        $this->ip        = $request->ip();

        $Usuario = new Usuario();
        $datos   = $Usuario->validaUsuario($this->idusuario, $this->clave, $this->ip);

        return response()->json($datos);
    }

    //
}
