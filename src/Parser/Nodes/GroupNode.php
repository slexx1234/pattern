<?php

namespace Slexx\Pattern\Parser\Nodes;

use Slexx\Pattern\Parser\Node;

class GroupNode extends Node
{
    /**
     * @var Node[]
     */
    protected $children = [];

    /**
     * @param Node $child
     */
    public function addChild($child)
    {
        $this->children[] = $child;
        $child->setParent($this);
        $child->setRoot($this->getRoot());
    }

    /**
     * @return string
     */
    public function compile()
    {
        return '(?:' . implode('', array_map(function($child) {
            return $child->compile();
        }, $this->children)) . ')?';
    }
}
