<?php

namespace Slexx\Pattern\Parser;

interface LexemeInterface
{
    /**
     * Получение отцтупа токена
     * @return int
     */
    public function getOffset();

    /**
     * Получение дочерних токенов
     * @return array
     */
    public function getChildren();

    /**
     * Компиляция токена
     * @return string
     */
    public function compile();
}
