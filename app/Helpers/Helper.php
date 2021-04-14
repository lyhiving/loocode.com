<?php


namespace App\Helpers;


class Helper
{
    /**
     * @param string $value
     * @return bool|int|mixed|string
     */
    public static function formatValue(string $value)
    {
        if ($value === 'true') {
            $formatValue = true;
        } elseif ($value === 'false') {
            $formatValue = false;
        } elseif (in_array(substr($value, 0, 1), ['[', '{'])) {
            $formatValue = json_decode($value);
        } elseif (is_numeric($value)) {
            $formatValue = (int)$value;
        } else {
            $formatValue = $value;
        }
        return $formatValue;
    }
}
