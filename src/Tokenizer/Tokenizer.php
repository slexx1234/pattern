<?php

namespace Slexx\Pattern\Tokenizer;

class Tokenizer
{
    const T_OPENING_GROUP = 1;
    const T_CLOSING_GROUP = 2;
    const T_OPENING_PARAM = 3;
    const T_CLOSING_PARAM = 4;
    const T_PARAM_NAME = 5;
    const T_PARAM_SEPARATOR = 6;
    const T_PARAM_RULE = 7;
    const T_TEXT = 8;

    /**
     * @param string $pattern
     * @return Token[]
     * @throws TokenizerException
     */
    public static function tokenize($pattern)
    {
        $result = [];

        $len = mb_strlen($pattern);
        for($i = 0; $i < $len; $i++) {
            $buffer1 = $pattern[$i];

            switch($buffer1) {
                case '[';
                    $result[] = new Token($i, '[', self::T_OPENING_GROUP);
                    break;

                case ']':
                    $result[] = new Token($i, ']', self::T_CLOSING_GROUP);
                    break;

                case '<':
                    if (preg_match('/<([^:>]+)(?::([^>]+))?>/', substr($pattern, $i), $matches)) {
                       $result[] = new Token($i, '<', self::T_OPENING_PARAM);
                       $result[] = new Token($i + 1, $matches[1], self::T_PARAM_NAME);
                       if (isset($matches[2])) {
                           $result[] = new Token($i + 1 + strlen($matches[1]), ':', self::T_PARAM_SEPARATOR);
                           $result[] = new Token($i + 2 + strlen($matches[1]), $matches[2], self::T_PARAM_RULE);
                           $result[] = new Token($i + 2 + strlen($matches[1]) + strlen($matches[2]), '>', self::T_CLOSING_PARAM);
                       } else {
                           $result[] = new Token($i + 1 + strlen($matches[1]), '>', self::T_CLOSING_PARAM);
                       }
                       $i += strlen($matches[0]) - 1;
                    } else {
                        throw new TokenizerException('Ошибка синтаксиса параметра!');
                    }
                    break;

                default:
                    preg_match('/(?=[\[\]<>:]|^)[^\[\]<>:]+(?=[\[\]<>:]|$)/', substr($pattern, $i), $matches);
                    $result[] = new Token($i, $matches[0], self::T_TEXT);
                    $i += strlen($matches[0]) - 1;
                    break;
            }
        }

        return $result;
    }
}
