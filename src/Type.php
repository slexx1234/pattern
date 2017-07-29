<?php

namespace Slexx\Pattern;

class Type
{
    /**
     * @param mixed $data
     * @param string  $type
     * @return int
     */
    public static function to($data, $type)
    {
        switch($type) {
            case 'int':
            case 'integer':
                return static::toInteger($data);

            case 'float':
            case 'double':
                return static::toFloat($data);

            case 'bool':
            case 'boolean':
                return static::toBoolean($data);

            default:
                return $data;
        }
    }

    /**
     * @param mixed $data
     * @return int
     */
    public static function toInteger($data)
    {
        return (int) $data;
    }

    /**
     * @param mixed $data
     * @return float
     */
    public static function toFloat($data)
    {
        return (float) $data;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public static function toBoolean($data)
    {
        return in_array($data, ['true', '1', 'on']);
    }
}
