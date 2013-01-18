<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if in get request
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Server $server The server widget
 */
class InGet extends WidgetProvider
{
    public function __invoke()
    {
        return 'GET' == strtoupper($this->server['REQUEST_METHOD']);
    }
}
