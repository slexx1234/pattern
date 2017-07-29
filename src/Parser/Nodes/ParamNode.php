<?php

namespace Slexx\Pattern\Parser\Nodes;

use Slexx\Pattern\Parser\Node;
use Slexx\Pattern\Parser\ParserException;
use Slexx\Pattern\Rules;

class ParamNode extends Node
{
    /**
     * @var null|string
     */
    protected $name = null;

    /**
     * @var null|string
     */
    protected $rule = null;

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $rule
     * @return void
     */
    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return string
     */
    public function compile()
    {
        $rule = Rules::DEFAULT_RULE;

        if ($this->rule !== null) {
            if ($this->getRoot()->hasRule($this->rule)) {
                $rule = $this->getRoot()->getRule($this->rule);
            } else if (Rules::has($this->rule)) {
                $rule = Rules::get($this->rule);
            } else {
                $rule = $this->rule;
            }
        }

        return "(?P<{$this->name}>{$rule})";
    }
}
