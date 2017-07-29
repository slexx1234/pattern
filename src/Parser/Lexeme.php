<?php

namespace Slexx\Pattern\Parser;

abstract class Lexeme implements LexemeInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var array
     */
    protected $children;

    /**
     * @param Context $context;
     * @param string $content
     * @param int $offset
     * @param array [$children]
     */
    public function __construct($context, $content, $offset, $children = [])
    {
        $this->context = $content;
        $this->content = $content;
        $this->offset = $offset;
        $this->children = $children;
    }

    /**
     * Получение отцтупа токена
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Получение дочерних токенов
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Компиляция токена
     * @return string
     */
    abstract public function compile();
}
