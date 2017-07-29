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
                if (isset($matches[$param['name']])) {
                    $result[$param['name']] = Type::to($matches[$param['name']], $param['type']);
                } else {
                    $result[$param['name']] = null;
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
}
