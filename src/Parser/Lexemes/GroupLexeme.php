<?php

namespace Slexx\Pattern\Parser\Lexemes;

use Slexx\Pattern\Parser\Lexeme;

class GroupLexeme extends Lexeme
{
    /**
     * Компиляция токена
     * @return string
     */
    public function compile()
    {
        $group = '';
        foreach($this->children as $child) {
            $group .= $child->compile();
        }
        return "(?:$group)?";
    }
}
