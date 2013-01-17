<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if in post request
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method string server(string $name) Returns the request server parameter
 */
class InPost extends WidgetProvider
{
    public function __invoke()
    {
        return 'POST' == strtoupper($this->server('REQUEST_METHOD'));
    }
}
