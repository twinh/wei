<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Check if in get request
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class InGet extends Widget
{
    public function __invoke()
    {
        return 'GET' == strtoupper($this->server('REQUEST_METHOD'));
    }
}
