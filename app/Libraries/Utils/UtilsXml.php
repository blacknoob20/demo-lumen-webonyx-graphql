<?php

namespace App\Libraries\Utils;

class UtilsXml
{
    public static function xml2array($xml)
    {
        if ($xml == '') return [];

        $simpleXmlObject = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$xml);

        if($simpleXmlObject === false) return [];

        $arr = UtilsArray::array_change_key_case_recursive(json_decode(json_encode($simpleXmlObject),1));

        return current($arr);
    }
}
