<?php

namespace Slexx\Pattern\Parser;

use Slexx\Pattern\Pattern;

class Root
{
    /**
     * @var Node[]
     */
    protected $children = [];

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param Node $child
     * @return void
     */
    public function addChild($child)
    {
        $this->children[] = $child;
        $child->setParent($this);
        $child->setRoot($this);
    }

    /**
     * @param string $name
     * @param string $rule
     * @return void
     */
    public function setRule($name, $rule)
    {
        $this->rules[$name] = $rule;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRule($name)
    {
        return isset($this->rules[$name]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getRule($name)
    {
        return $this->hasRule($name) ? $this->rules[$name] : null;
    }

    /**
     * @return string
     */
    public function compile()
    {
        return '/^' . implode('', array_map(function($child) {
            return $child->compile();
        }, $this->children)) . '$/';
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        foreach($this->params as $param) {
            if ($param['name'] === $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $name
     * @param string $type
     * @return void
     */
    public function addParam($name, $type = 'string')
    {
        $this->params[] = [
            'name' => $name,
            'type' => $type,
        ];
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return $this
     */
    public function getRoot()
    {
        return $this;
    }
}

