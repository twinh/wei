<?php

namespace Wei\Db;

/**
 * @internal may be rename
 */
class Raw
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
