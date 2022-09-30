<?php

namespace App\Models\Oracle;

use Illuminate\Support\Facades\Log;

class Adapter extends Conexion
{
    protected $sql;
    protected $clob_data;
    protected $clob_field;
    protected $clob_data2;
    protected $clob_field2;
    protected $msjAlerta;
    protected $tipoAlerta;
    protected $idAI;

    public function __construct()
    {
        parent::__construct();

        $this->conectar();

        if (!$this->cnx) {
            $e = oci_error();
            $this->__errorDB($e['code'], $e['message'], "");
            exit;
        }
    }

    public function setSql($value)
    {
        $this->sql = $value;
    }
    public function setClob_data($value)
    {
        $this->clob_data = $value;
    }
    public function setClob_field($value)
    {
        $this->clob_field = $value;
    }
    public function setClob_data2($value)
    {
        $this->clob_data2 = $value;
    }
    public function setClob_field2($value)
    {
        $this->clob_field2 = $value;
    }
    public function setMsjAlerta($value)
    {
        $this->msjAlerta = $value;
    }
    public function getMsjAlerta()
    {
        return $this->msjAlerta;
    }
    public function setTipoAlerta($value)
    {
        $this->tipoAlerta = $value;
    }
    public function getTipoAlerta()
    {
        return $this->tipoAlerta;
    }
    public function getIdAI()
    {
        return $this->idAI;
    }
    public function setName_idAI($value)
    {
        $this->name_idAI = $value;
    }

    public function selectOne()
    {
        $reg = $this->selectAll();

        return ($reg[0] ?? $reg);
    } // end selectOne

    public function selectAll()
    {
        $registros  = [];
        $descriptor = false;
        $stmt       = oci_parse($this->cnx, $this->sql);

        if (!oci_execute($stmt)) {
            $this->__errorDB(NULL, NULL, $stmt);
            oci_free_statement($stmt);

            return $registros;
        }

        $this->__log($this->sql, __LINE__, 'notice');

        if (strripos($this->sql, 'AS MFRC from dual') > 0) {
            $descriptor = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_LOBS)['MFRC'];

            oci_execute($descriptor);  // returned column value from the query is a ref cursor.
        }

        while ($reg = oci_fetch_array(($descriptor ?? $stmt), OCI_ASSOC + OCI_RETURN_LOBS)) {
            $registros[] = array_change_key_case($reg, CASE_LOWER);
        } // el indice se asigna automaticamente

        oci_free_statement($stmt);

        return $registros;
    } // end selectAll

    public function mantenimiento($blog = true)
    {
        $errCode    = 0;
        $errDesc    = '';
        $isSetCLOB1 = ($this->clob_field  != '' && $this->clob_data  != '');
        $isSetCLOB2 = ($this->clob_field2 != '' && $this->clob_data2 != '');
        $stmt       = oci_parse($this->cnx, $this->sql);

        if (strripos($this->sql, ', :errcode, :errdesc);') > 0) {
            if ($isSetCLOB1) {
                $clob = oci_new_descriptor($this->cnx, OCI_D_LOB);
                oci_bind_by_name($stmt, $this->clob_field, $clob, -1, OCI_B_CLOB);
                $clob->writetemporary($this->clob_data);
            }

            if ($isSetCLOB2) {
                $clob2 = oci_new_descriptor($this->cnx, OCI_D_LOB);
                oci_bind_by_name($stmt, $this->clob_field2, $clob2, -1, OCI_B_CLOB);
                $clob2->writetemporary($this->clob_data2);
            }

            oci_bind_by_name($stmt, ':errcode', $errCode, 10);
            oci_bind_by_name($stmt, ':errdesc', $errDesc, 4000);
        }

        $this->__log(($blog ? $this->sql . ($this->clob_data != '' ? '[' . $this->clob_field . ':' . $this->clob_data . ']' : 'vacio') . ($this->clob_data2 != '' ? '[' . $this->clob_field2 . ':' . $this->clob_data2 . ']' : 'vacio') : substr($this->sql, 0, strpos($this->sql, '\','))), __LINE__, 'critical');
        $execute = oci_execute($stmt);

        if ($isSetCLOB1) $clob->free();
        if ($isSetCLOB2) $clob2->free();

        if (!$execute) {
            $this->__errorDB(NULL, NULL, $stmt);
            oci_free_statement($stmt);
            return false;
        }

        oci_free_statement($stmt);

        if ($errCode != 0) {
            $this->__errorDB($errCode, $errDesc, NULL);
            return false;
        } elseif ($errDesc != 'OK') {
            $this->idAI = $errDesc; // recupero valor de clave autogenerado
        }

        return true;
    } // end mantenimiento

    public function matriz2lista($listaItems)
    {
        $lista = [];
        // convierte recordset a matriz de 2 dimenciones
        if (is_array($listaItems)) {
            foreach ($listaItems as $valor) {
                $lista[$valor['CODE']] = $valor['DESCRIPTION'];
            }
        }
        return $lista;
    } // end matriz2lista()

    private function __errorDB($errCode, $errDesc, $stmt)
    {
        if (is_null($errCode) && is_null($errDesc)) {
            $e = oci_error($stmt);  // For oci_execute errors pass the statement handle

            if ($e != false) {
                $errCode = -$e['code'];
                $errDesc = $e['message'];
            }
        } else {
            // errores aplicativos
            if ($errCode <= -20000 && $errCode >= -20999) {
                // solo texto de error warning
                $pi = strpos($errDesc, "ORA-");

                if ($pi !== false) {
                    $pi = strpos($errDesc, ":") + 1;
                    $pf = strpos($errDesc, "ORA-", $pi);
                    eval('$errDescShow = substr($errDesc,$pi' . (($pf > 0) ? ',($pf-$pi)' : '') . ');');
                } else {
                    $errDescShow = $errDesc;
                }

                $this->msjAlerta = "Aviso: " . $errDescShow;
                $this->tipoAlerta = "W";
            } else {
                $this->tipoAlerta = "E";

                if ($errCode == -1) {
                    $this->msjAlerta = "Error Registro duplicado";
                    $this->tipoAlerta = "W";
                } elseif ($errCode == -4068) {
                    $this->msjAlerta = "Paquetes descompilados, por favor reintente.";
                    //Reiniciar el estado del paquete
                    $stmt1 = oci_parse($this->cnx, 'begin dbms_session.modify_package_state(2); end;');
                    oci_execute($stmt1);
                    oci_free_statement($stmt1);
                } else {
                    $this->msjAlerta = "En estos momentos no podemos atenderlo, intente luego de unos minutos (00" . substr($errCode, 1) . "0)";
                }
            }

            $this->__log("$errCode\t$errDesc\t{$this->sql}", __LINE__, 'error');
        }
    } //__errorDB()

    protected function __log($msg, $line, $type = 'notice')
    {
        Log::$type("$msg\t" . get_class($this) . "\t$line\t{$_SERVER['HTTP_HOST']}\t{$_SERVER['REMOTE_ADDR']}");
    }

    //OJOT solo para desarrollo, hay que quitarlo en produccion
    protected  function showobject($color)
    {
        echo "<br><span style='color:" . (is_null($color) ? "blue" : $color) . "'>";
        print_r($this);
        echo '</span>';
    } //showoject()
    protected  function showsql($color)
    {
        echo "<br><span style='color:" . (is_null($color) ? "green" : $color) . "'>" . htmlentities($this->sql) . '</span>';
    } //showoject()


} // end class
