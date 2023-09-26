<?php

namespace App\Models\Oracle\SE;

use App\Libraries\JWT\JWebToken;
use App\Libraries\Utils\UtilsXml;
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
            . ($this->isNvl($this->clave) ? ', null' : sprintf(", '%s'", md5($this->clave)))
            . sprintf(", 'NOMBREPC', '%s', '%s'", $this->ip, $this->idusuario_log);
        $this->setSql("begin SE_PQ_SESSION.p_Inicia_Sesion($par, true, :errcode, :errdesc); end;");

        return $this->mantenimiento(false);
    } //iniciar_sesion()

    /**
     * Obtiene los usuarios asignados a una persona
     * @param number $idpersona codigo de persona/empleado
     * @return array
     */
    public function getUsuariosXPersona($idpersona)
    {
        $par =  ($this->isNvl($idpersona) ? 'null' : sprintf('%s', $idpersona));
        $this->setSql("select SE_PQ_USUARIO.f_getUsuariosXPersona($par) AS MFRC from dual");
        $reg = $this->selectAll();

        return $reg;
    }

    public function getFull()
    {
        $par = ($this->isNvl($this->idusuario) ? 'null' : sprintf("'%s'", strtoupper($this->idusuario)))
            . ($this->isNvl($this->idusuariored) ? ', null' : sprintf(", '%s'", strtoupper($this->idusuariored)));
        $this->setSql("select SE_PQ_USUARIO.f_getFull($par) AS MFRC from dual ");
        $reg = $this->selectOne();

        // Convertir XML a arreglos
        $reg['companeros'] = UtilsXml::xml2array($reg['companeros']);
        $reg['sistemas']   = UtilsXml::xml2array($reg['sistemas']);
        $reg['areas']      = UtilsXml::xml2array($reg['areas']);
        $reg['modulos']    = UtilsXml::xml2array($reg['modulos']);

        return $reg;
    } // end getFull()

    public function getPregunta()
    {
        $par = sprintf("'%s', %d, '%s'", strtoupper($this->idusuario), $this->idempleado, $this->numdocumento);
        $this->setSql("select SE_PQ_USUARIO.f_getPregunta($par) AS MFRC from dual");
        $reg = $this->selectOne();

        return $reg;
    }  // end getPregunta()

    public function get_modulos()
    {
        $par = sprintf("'%s', %d", $this->idusuario, $this->idsistema);
        $this->setSql("select SE_PQ_Session.f_ModuloXUsuario($par) AS MFRC from dual");
        $reg = $this->selectAll();

        return $reg;
    } //get_modulos()

    public function validaPregunta()
    {
        $par = sprintf("'%s', %d, '%s, %d', '%s'", strtoupper($this->idusuario), $this->numeropregunta, $this->respuesta, $this->idempleado, $this->numdocumento);
        $this->setSql("select SE_PQ_USUARIO.f_validaPregunta($par) AS MFRC from dual");
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
