<?php //session_start();

namespace App\Models\Oracle;

use Exception;

class Conexion
{
    //atributos
    private $host;
    private $port;
    private $tns_name;
    private $service_name;
    private $usuario;
    private $passwd;
    private $charset;

    protected $cnx = NULL;

    public function __construct()
    {
        $this->host         = env('DB_HOST');
        $this->port         = env('DB_PORT');
        $this->tns_name     = env('DB_TNS');
        $this->service_name = env('DB_SID');
        $this->usuario      = env('DB_USERNAME');
        $this->passwd       = env('DB_PASSWORD');
        $this->charset      = env('DB_CHARSET');
    }

    public function conectar()
    {
        $this->cnx = oci_pconnect($this->usuario, $this->passwd, $this->tns_name, $this->charset);
        // $this->cnx = oci_pconnect($this->usuario, $this->passwd, "//{$this->host}:{$this->port}/$this->service_name", $this->charset);

        if (!$this->cnx) throw new Exception('No hay conexion con la Base de Datos. Comuniquese con Informatica', -0001);

        return $this->cnx;
    }
} // end class
