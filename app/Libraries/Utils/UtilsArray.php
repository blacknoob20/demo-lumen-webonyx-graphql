<?php

namespace App\Libraries\Utils;

class UtilsArray
{

    /**
     * key case changer. changes key recursively
     * Source php.net
     * @param array $arr  Array multidimensional
     * @param int   $case CASE_UPPER | CASE_LOWER
     * @return array
     */
    public static function array_change_key_case_recursive($arr, $case = CASE_LOWER)
    {
        return array_map(function ($item) use ($case) {
            if (is_array($item))
                $item = self::array_change_key_case_recursive($item, $case);
            return $item;
        }, array_change_key_case($arr, $case));
    }

    /**
     * converts an array to XML string
     * @param array   $elements Array multidimensional  Array multidimensional
     * @param string  $prefijo Exclude keys contains prefijo
     * @param string  $campo Key of array
     * @param string  $grupo TagName of node
     * @return string
     */
    public static function array2xml($elements, $prefijo, $campo, $grupo)
    {
        $sXML = '';
        // genera un string XML para el arreglo enviado
        if (is_array($elements)) {
            $arrTmp = $elements[$campo];
            $nRows = count($arrTmp);
            if ($nRows > 0) $sXML = '<?xml version="1.0" encoding="UTF-8"?><' . $grupo . 's>';

            for ($ind = 0; $ind < $nRows; $ind++) {
                $sXML .= '<' . $grupo . '>';
                foreach ($elements as $ind2 => $valor2) {
                    if (isset($valor2[$ind]) && substr($ind2, 0, 3) != $prefijo) {
                        $valor3 = $valor2[$ind];
                        $sXML .= '<' . $ind2 . '>' . $valor3 . '</' . $ind2 . '>';
                    }
                }
                $sXML .= '</' . $grupo . '>';
            }
            if ($nRows > 0) $sXML .= '</' . $grupo . 's>';
        } //(is_array($elements))
        return $sXML;
    } // fin de arrayToXml()

}
