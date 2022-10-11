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
}
