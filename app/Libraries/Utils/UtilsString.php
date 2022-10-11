<?php

namespace App\Libraries\Utils;

class UtilsString
{
    /**
     * Evalúa si una palabra tiene como mínimo un caracter con mayúscula.
     * @param string $str palabra a evaluar
     * @return boolean
     */
    public static function isUpperCase($str)
    {
        return preg_match('~^\p{Lu}~u', $str);
    }
}
