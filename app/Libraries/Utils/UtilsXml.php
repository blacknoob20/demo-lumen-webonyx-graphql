<?php

namespace App\Libraries\Utils;

class UtilsXml
{
    /**
     * Convetir un XML a un arreglo multidimensional
     * @param string $xml
     * @return array
     */
    public static function xml2array($xml)
    {
        if ($xml == '') return [];

        $simpleXmlObject = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>' . $xml);

        if ($simpleXmlObject === false || empty($simpleXmlObject)) return [];

        $arr = current(json_decode(json_encode($simpleXmlObject), 1));
        // Convertir los key names
        if (UtilsString::isUpperCase(key($arr[0])))
            $arr = UtilsArray::array_change_key_case_recursive($arr);

        return $arr;
    }
}
