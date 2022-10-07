<?php

namespace App\Models\Oracle\SE;

use App\Libraries\JWT\JWebToken;
use App\Models\Oracle\Adapter;

class Usuario extends Adapter
{
    private $idsistema;
    private $idusuario;
    private $idusuariored;
    private $clave;
    private $idusuario_log;
    private $ip;
    private $idempleado;
    private $numdocumento;
    private $numeropregunta;
    private $respuesta;

    public function __construct($props = [])
    {
        parent::__construct();

        $this->idsistema      = $props['idsistema'] ?? NULL;
        $this->idusuario      = $props['idusuario'] ?? NULL;
        $this->idusuariored   = $props['idusuariored'] ?? NULL;
        $this->clave          = $props['clave'] ?? NULL;
        $this->idusuario_log  = $props['idusuario_log'] ?? NULL;
        $this->ip             = $props['ip'] ?? NULL;
        $this->idempleado     = $props['idempleado'] ?? NULL;
        $this->numdocumento   = $props['numdocumento'] ?? NULL;
        $this->pregunta       = $props['pregunta'] ?? NULL;
        $this->numeropregunta = $props['numeropregunta'] ?? NULL;
        $this->respuesta      = $props['respuesta'] ?? NULL;
    }

    //validacion de usuario contraseña en Ldap u oracle
    public function validaUsuario()
    {
        // Conectar LDAP
        list($isNetUser, $foto) = $this->_verificarUsuarioLDAP($this->idusuariored, $this->clave);

        if ($isNetUser) {
            $existeLdap      = ($this->getMsjAlerta() == '');
            $this->idusuario = NULL;
            $this->clave     = NULL;
        } else {
            $existeLdap = true;
            $this->idusuariored = NULL;
        }

        if ($existeLdap) {
            if ($this->iniciar_sesion()) {
                $reg     = $this->getFull();
                $payload = ['uid' => $reg['idempleado'], 'nick' => ($this->idusuario ?? $this->idusuariored)];

                return [
                    'foto'  => $foto,
                    'token' => JWebToken::getToken($payload),
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

    public function getFull()
    {
        $par = (is_null($this->idusuario) || $this->idusuario == '' ? "null" : sprintf("'%s'", strtoupper($this->idusuario)))
            . (is_null($this->idusuariored) || $this->idusuariored == '' ? ", null" : sprintf(", '%s'", strtoupper($this->idusuariored)));

        $sql = sprintf("select SE_PQ_USUARIO.f_getFull(%s) AS MFRC from dual ", $par);
        $this->setSql($sql);
        $reg = $this->selectOne();

        return $reg;
    } // end getFull()

    public function getPregunta()
    {
        $par = sprintf("'%s'", strtoupper($this->idusuario))
            . sprintf(', %d', $this->idempleado)
            . sprintf(", '%s'", $this->numdocumento);

        $sql = sprintf('select SE_PQ_USUARIO.f_getPregunta(%s) AS MFRC from dual ', $par);
        $this->setSql($sql);
        $reg = $this->selectOne();

        return $reg;
    }  // end getPregunta()

    public function get_modulos() {
        $par = sprintf("'%s', %d", $this->idusuario, $this->idsistema);

        $sql = "select SE_PQ_Session.f_ModuloXUsuario($par) AS MFRC from dual";
        $this->setSql($sql);
        $reg = $this->selectAll();

        return $reg;
    } //get_modulos()

    public function validaPregunta()
    {
        $par = sprintf("'%s'", strtoupper($this->idusuario))
            . sprintf(', %d', $this->numeropregunta)
            . sprintf(", '%s'", $this->respuesta)
            . sprintf(', %d', $this->idempleado)
            . sprintf(", '%s'", $this->numdocumento);

        $sql = sprintf('select SE_PQ_USUARIO.f_validaPregunta(%s) AS MFRC from dual', $par);
        $this->setSql($sql);
        $reg = $this->selectOne();

        return $reg;
    }  //end validaPregunta()

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
