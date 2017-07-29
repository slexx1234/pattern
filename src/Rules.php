<?php

namespace Slexx\Pattern;

class Rules
{
    const DEFAULT_RULE = '.+';

    /**
     * @var array
     */
    protected static $rules = [
        'integer' => '(?:[1-9][0-9]*|0)',
        'float' => '(?:[1-9][0-9]*|0)\.[0-9]*',
        'number' => '(?:[1-9][0-9]*|0)(?:\.[0-9]*)?',
        'string' => '(?:.|[^.])+',
        'boolean' => 'true|false',
        'word' => '\w+',
        'slug' => '[\s\d_\-]+'
    ];

    protected static $aliases = [
        'bool' => 'boolean',
        'int' => 'integer',
        'double' => 'float',
    ];

    /**
     * Добавляет псевоним для правила
     * @param string $name
     * @param string $alias
     * @return void
     */
    public static function alias($name, $alias)
    {
        static::$aliases[$alias] = $name;
    }


    /**
     * @param string $name
     * @return string
     */
    protected static function name($name)
    {
        return isset(static::$aliases[$name]) ? static::$aliases[$name] : $name;
    }

    /**
     * Установка правила
     * @param string $name - Имя правила
     * @param string $rule - Регулярное выражение для правила
     * @return void
     */
    public static function set($name, $rule)
    {
        static::$rules[static::name($name)] = $rule;
    }

    /**
     * Удаление правила
     * @param string $name - Имя правила
     */
    public static function remove($name)
    {
        unset(static::$rules[static::name($name)]);
    }

    /**
     * Проверка существования правила
     * @param string $name - Имя правила
     * @return bool
     */
    public static function has($name)
    {
        return isset(static::$rules[static::name($name)]);
    }

    /**
     * Получение правила
     * @param string $name - Имя правила
     * @return string|null
     */
    public static function get($name)
    {
        return static::has(static::name($name)) ? static::$rules[static::name($name)] : null;
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
