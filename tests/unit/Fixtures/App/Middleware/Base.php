<?php

namespace WeiTest\Fixtures\App\Middleware;

/**
 * @property \Wei\Response $response
 */
class Base extends \Wei\Base
{
    /**
     * Returns current class name
     *
     * @return string
     */
    public static function className()
    {
        return static::class;
    }
}
