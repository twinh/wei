<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if the current request method is specified string
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Request $request The request widget
 */
class InMethod extends AbstractWidget
{
    /**
     * Check if the current request method is the specified string
     * 
     * @param string $method The method name to be compared
     * @return bool
     */
    public function __invoke($method)
    {
        return $this->request->inMethod($method);
    }
}
