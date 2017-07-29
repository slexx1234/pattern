<?php

namespace Slexx\Pattern\Parser\Lexemes;

use Slexx\Pattern\Rules;
use Slexx\Pattern\Parser\Lexeme;

class ParamLexeme extends Lexeme
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $rule;

    /**
     * @param \Slexx\Pattern\Parser\Context $context;
     * @param string $content
     * @param string $name
     * @param string|null $rule
     * @param int $offset
     * @param array [$children]
     */
    public function __construct($context, $content, $name, $rule, $offset, $children = [])
    {
        parent::__construct($context, $content, $offset, $children);
        $this->name = $name;
        $this->rule = $rule;
    }

    /**
     * Компиляция токена
     * @return string
     */
    public function compile()
    {
        $name = $this->name;
        $rule = Rules::DEFAULT_RULE;

        if ($this->rule !== null) {
            if ($this->context->hasRule($this->rule)) {
                $rule = $this->context->getRule($this->rule);
            } else if (Rules::has($this->rule)) {
                $rule = Rules::get($this->rule);
            } else {
                $rule = $this->rule;
            }
        }

        return "(?P<$name>$rule)";
    }
}
