<?php

namespace Slexx\Pattern\Parser;

class ParserException extends \Exception
{
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @param string $message
     * @param int $offset
     * @param int $code
     */
    public function __construct($message, $offset = 0, $code = 0)
    {
        parent::__construct($message, $code);
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}

