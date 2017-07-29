<?php

namespace Slexx\Pattern\Parser;

use Slexx\Pattern\Tokenizer\Token;
use Slexx\Pattern\Tokenizer\Tokenizer;
use Slexx\Pattern\Parser\Nodes\TextNode;
use Slexx\Pattern\Parser\Nodes\GroupNode;
use Slexx\Pattern\Parser\Nodes\ParamNode;

class Parser
{
    /**
     * @param string $pattern
     * @return Root
     * @throws \Slexx\Pattern\Tokenizer\TokenizerException|ParserException
     */
    public static function parseString($pattern)
    {
        $root = new Root;
        static::parseTokens(Tokenizer::tokenize($pattern), $root);
        return $root;
    }

    /**
     * @param \Slexx\Pattern\Tokenizer\Token[] $tokens
     * @param Root|Node|GroupNode $root
     * @throws ParserException
     */
    public static function parseTokens($tokens, $root)
    {
        $length = count($tokens);
        for($i = 0; $i < $length;) {
            switch($tokens[$i]->getType()) {
                case Tokenizer::T_TEXT:
                    $i += static::text(array_slice($tokens, $i), $root);
                    break;

                case Tokenizer::T_OPENING_GROUP:
                    $i += static::group(array_slice($tokens, $i), $root);
                    break;

                case Tokenizer::T_OPENING_PARAM:
                    $i += static::param(array_slice($tokens, $i), $root);
                    break;

                default:
                    var_dump($tokens[$i]);
                    throw new ParserException('Не ожиданный токен!', $tokens[$i]->getOffset(), 1);
                    break;
            }
        }
    }

    /**
     * @param Token[] $tokens
     * @param GroupNode|Root $root
     * @return int
     * @throws ParserException
     */
    protected static function param($tokens, $root)
    {
        static::checkParamSyntax($tokens);

        $param = new ParamNode();
        $root->addChild($param);
        $name = $tokens[1]->getContent();

        if ($root->getRoot()->hasParam($name)) {
            throw new ParserException('Не уникальное имя параметра!', $tokens[1]->getOffset(), 12);
        }
        $param->setName($name);

        if (isset($tokens[3]) && $tokens[3]->getType() === Tokenizer::T_PARAM_RULE) {
            $type = $tokens[3]->getContent();
            if (in_array($type, ['int', 'integer', 'float', 'double', 'bool', 'boolean'])) {
                $root->getRoot()->addParam($name, $type);
            } else {
                $root->getRoot()->addParam($name);
            }
            $param->setRule($type);
            return 5;
        } else {
            $root->getRoot()->addParam($name);
            return 3;
        }
    }

    /**
     * @param Token[] $tokens
     * @return void
     * @throws ParserException
     */
    protected static function checkParamSyntax($tokens)
    {
        if (isset($tokens[0]) && $tokens[0]->getType() === Tokenizer::T_OPENING_PARAM) {
            if (!isset($tokens[1])) {
                throw new ParserException('Ожидается имя параметра!', $tokens[0]->getOffset(), 2);
            }

            if ($tokens[1]->getType() !== Tokenizer::T_PARAM_NAME) {
                throw new ParserException('Ожидается имя параметра!', $tokens[1]->getOffset(), 3);
            }

            if (!isset($tokens[2])) {
                throw new ParserException('Ожидается закрытие параметра или указание правила параметра!', $tokens[1]->getOffset(), 4);
            }

            if ($tokens[2]->getType() === Tokenizer::T_PARAM_SEPARATOR) {
                if (!isset($tokens[3])) {
                    throw new ParserException('Ожидается указание правила параметра!', $tokens[2]->getOffset(), 5);
                }

                if ($tokens[3]->getType() !== Tokenizer::T_PARAM_RULE) {
                    throw new ParserException('Ожидается указание правила параметра!', $tokens[3]->getOffset(), 6);
                }

                if (!isset($tokens[4])) {
                    throw new ParserException('Ожидается закрытие параметра!', $tokens[3]->getOffset(), 7);
                }

                if ($tokens[4]->getType() !== Tokenizer::T_CLOSING_PARAM) {
                    throw new ParserException('Ожидается закрытие параметра!', $tokens[4]->getOffset(), 8);
                }
            } else if ($tokens[2]->getType() !== Tokenizer::T_CLOSING_PARAM) {
                throw new ParserException('Ожидается закрытие параметра или указание правила параметра!', $tokens[2]->getOffset(), 9);
            }
        }
    }

    /**
     * @param Token[] $tokens
     * @param GroupNode|Root $root
     * @return int
     */
    protected function group($tokens, $root)
    {
        $groupTokens = static::groupTokens($tokens);
        if (count($groupTokens) > 2) {
            $group = new GroupNode();
            $root->addChild($group);
            static::parseTokens(array_slice($groupTokens, 1, -1), $group);
        }
        return count($groupTokens);
    }

    /**
     * @param Token[] $tokens
     * @param GroupNode|Root $root
     * @return int
     */
    protected function text($tokens, $root)
    {
        $root->addChild(new TextNode($tokens[0]->getContent()));
        return 1;
    }

    /**
     * @param Token[] $tokens
     * @return Token[]
     * @throws ParserException
     */
    protected function groupTokens($tokens)
    {
        $level = 0;
        $children = [];
        foreach($tokens as $token) {
            if ($token->getType() === Tokenizer::T_CLOSING_GROUP) $level--;
            if ($token->getType() === Tokenizer::T_OPENING_GROUP) $level++;
            $children[] = $token;
            if ($level === 0) break;
        }
        if ($level !== 0) {
            throw new ParserException('Не закрыты ' . $level . ' группы!', $tokens[0]->getOffset(), 10);
        }
        return $children;
    }
}