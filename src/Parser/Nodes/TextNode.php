<?php

namespace Slexx\Pattern\Parser\Nodes;

use Slexx\Pattern\Parser\Node;

class TextNode extends Node
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function compile()
    {
        return preg_quote($this->text, '/');
    }
}
