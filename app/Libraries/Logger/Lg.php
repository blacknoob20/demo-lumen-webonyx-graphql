<?php

namespace App\Libraries\Logger;

use Illuminate\Support\Facades\Log;

class Lg
{
    /**
     * Controlador para renovar el TOKEN
     * @param String $msg
     * @param String $code
     * @param String $error
     * @param String $class
     * @param Int    $line
     * @param String $type emergency, alert, critical, error, warning, notice, info
     *
     */
    public static function w($msg, $code, $error, $class, $line, $type = 'info')
    {
        $headers = getallheaders();
        $ug      = (isset($headers['User-Agent'])) ? $headers['User-Agent'] : 'NONE';
        $agent   = trim(substr($ug,strrpos($ug,' ')));

        Log::$type("{$_SERVER['REMOTE_ADDR']}\t$class($line)\t$code\t$msg\t$error\t$agent");
    }
}
