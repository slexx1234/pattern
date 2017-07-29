<?php

namespace Slexx\Pattern;

class Rules
{
    /**
     * @var array
     */
    protected static $rules = [
        'int' => '[0-9]+',
        'id' => '[0-9]+',
    ];

    /**
     * Установка правила
     * @param string $name - Имя правила
     * @param string $rule - Регулярное выражение для правила
     * @return void
     */
    public static function set($name, $rule)
    {
        static::$rules[$name] = $rule;
    }

    /**
     * Удаление правила
     * @param string $name - Имя правила
     */
    public static function remove($name)
    {
        unset(static::$rules[$name]);
    }

    /**
     * Проверка существования правила
     * @param string $name - Имя правила
     * @return bool
     */
    public static function has($name)
    {
        return isset(static::$rules[$name]);
    }

    /**
     * Получение правила
     * @param string $name - Имя правила
     * @return string|null
     */
    public static function get($name)
    {
        return static::has($name) ? static::$rules[$name] : null;
    }

    /**
     * Получение всех правил
     * @return array
     */
    public static function all()
    {
        return static::$rules;
    }
}
