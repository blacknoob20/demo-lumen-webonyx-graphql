<?php

namespace App\Models\Oracle\SE;

use App\Libraries\JWT\JWebToken;
use App\Models\Oracle\Adapter;

class Usuario extends Adapter
{
    private $idusuario;
    private $idusuariored;
    private $clave;
    private $idusuario_log;
    private $ip;

    public function __construct()
    {
        parent::__construct();

        $this->idusuario     = '';
        $this->idusuariored  = '';
        $this->clave         = '';
        $this->idusuario_log = '';
        $this->ip            = '';
    }

    //validacion de usuario contraseña en Ldap u oracle
    public function validaUsuario($idusuario, $clave, $ip)
    {
        $this->idusuario_log = $idusuario;
        $this->ip            = $ip;
        // Conectar LDAP
        list($isNetUser, $foto) = $this->_verificarUsuarioLDAP($idusuario, $clave);

        // Cambiar el usuario a usuario de red
        if ($isNetUser) {
            $existeLdap   = ($this->getMsjAlerta() == '');
            $this->idusuariored = $idusuario;
            $this->idusuario    = NULL;
            $this->clave        = NULL;
        } else {
            $existeLdap = true;
            $this->idusuariored = NULL;
            $this->idusuario    = $idusuario;
            $this->clave        = $clave;
        }

        if ($existeLdap) {
            if ($this->iniciar_sesion()) {
                $reg     = $this->getFull($this->idusuario, $this->idusuariored);
                $payload = ['uid' => $reg['idempleado'], 'nick' => $idusuario];

                return [
                    'foto'  => $foto,
                    'token' => (new JWebToken())->getToken($payload),
                ];
            }
        }

        return false;
    } //validaUsuario()

    public function iniciar_sesion()
    {
        $par = sprintf("'%s'", strtoupper($this->idusuario))
            . (is_null($this->clave) ? ', null' : sprintf(",  '%s'", md5($this->clave)))
            . sprintf(", '%s'", 'NOMBREPC')
            . sprintf(", '%s'", $this->ip)
            . sprintf(", '%s'", $this->idusuario_log);
        $sql = sprintf("begin SE_PQ_SESSION.p_Inicia_Sesion(%s, true, :errcode, :errdesc); end;", $par);

        $this->setSql($sql);

        return $this->mantenimiento(false);
    } //iniciar_sesion()

    /**
     * Obtiene los usuarios asignados a una persona
     * @param number $idpersona codigo de persona/empleado
     * @return array
     */
    public function getUsuariosXPersona($idpersona)
    {
        $par =  (is_null($idpersona) || $idpersona == '' ? "null" : sprintf("%s", $idpersona));
        $sql = sprintf("select SE_PQ_Usuario.f_getUsuariosXPersona( %s) AS MFRC from dual ", $par);
        $this->setSql($sql);
        $reg = $this->selectAll();
        return $reg;
    }

    public function getFull($idusuario, $idusuariored)
    {
        $par =  (is_null($idusuario) || $idusuario == '' ? "null" : sprintf("'%s'", strtoupper($idusuario)))
            . (is_null($idusuariored) || $idusuariored == '' ? ", null" : sprintf(", '%s'", strtoupper($idusuariored)));
        $sql = sprintf("select SE_PQ_USUARIO.f_getFull(%s) AS MFRC from dual ", $par);

        $this->setSql($sql);
        $reg = $this->selectOne();

        return $reg;
    } // end getFull()

    private function _verificarUsuarioLDAP($idusuario, $clave)
    {
        $isNetUser = !(strpos($idusuario, '.') === false);

        // if ($isNetUser) {
        //     $ldapController = new Se_UsuarioLdap(strtolower($idusuario), $clave);

        //     if ($ldapController->validarUsuarioLdap()) {
        //         $this->setIdusuariored($idusuario);
        //         $reg = $ldapController->get($idusuario);

        //         return [
        //             $isNetUser,
        //             $reg['photo'],
        //         ];
        //     } else {
        //         $this->setMsjAlerta($ldapController->getMsgError());
        //     }
        // }

        return [
            $isNetUser,
            NULL,
        ];
    }
}
