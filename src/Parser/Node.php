<?php

namespace Slexx\Pattern\Parser;

class Node
{
    /**
     * @var null|Root|Node
     */
    protected $parent = null;

    /**
     * @var null|Root
     */
    protected $root = null;

    /**
     * @param null|Root $root
     * @return void
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return null|Root
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param null|Node|Root $parent
     * @return void
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return null|Node|Root
     */
    public function getParent()
    {
        return $this->parent;
    }
}

