<?php

namespace Slexx\Pattern\Parser\Lexemes;

use Slexx\Pattern\Parser\Lexeme;

class StringLexeme extends Lexeme
{
    /**
     * Компиляция токена
     * @return string
     */
    public function compile()
    {
        return preg_quote($this->content);
    }
}
