<?php

namespace Slexx\Pattern\Tokenizer;

class Token
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $type;

    /**
     * @param int $offset
     * @param string $content
     * @param int $type
     */
    public function __construct($offset, $content, $type)
    {
        $this->offset = $offset;
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}

