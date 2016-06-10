<?php

namespace WeiTest\Fixtures\app\middleware;

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
        return get_called_class();
    }
}