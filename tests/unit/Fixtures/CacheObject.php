<?php

namespace WeiTest\Fixtures;

/**
 * For PHP 7.2
 */
class CacheObject
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function __set_state($props)
    {
        return new self($props['value']);
    }
}
