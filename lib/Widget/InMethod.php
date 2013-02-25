<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * InMethod
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Server $server The server widget
 */
class InMethod extends AbstractWidget
{
    public function __invoke($method)
    {
        return 0 === strcasecmp($this->server['REQUEST_METHOD'], $method);
    }
}
