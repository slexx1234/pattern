<?php

namespace Slexx\Pattern;

use Slexx\Pattern\Parser\Parser;

class Pattern
{
    /**
     * @var \Slexx\Pattern\Parser\Root
     */
    protected $context;

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->context = Parser::parseString($pattern);
    }

    /**
     * @param string $name
     * @param string $rule
     * @return void
     */
    public function rule($name, $rule)
    {
        $this->context->setRule($name, $rule);
    }

    /**
     * @param string $string
     * @return null|array
     */
    public function match($string)
    {
        if (preg_match($this->context->compile(), $string, $matches)) {
            $result = [];
            foreach($this->context->getParams() as $param) {
                $name = $param['name'];
                $type = $param['type'];

                if (isset($matches[$name]) && !empty(trim($matches[$name]))) {
                    $result[$name] = Type::to($matches[$name], $type);
                    continue;
                }

                if (isset($this->defaults[$name])) {
                    $result[$name] = $this->defaults[$name];
                } else if ($type === 'bool' || $type === 'boolean') {
                    $result[$name] = false;
                } else {
                    $result[$name] = null;
                }
            }
            return $result;
        }
        return null;
    }

    /**
     * @param string $string
     * @return int
     */
    public function is($string)
    {
        return (bool) preg_match($this->context->compile(), $string);
    }

    /**
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function default($name, $data)
    {
        $this->defaults[$name] = $data;
    }
}
