<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Check if in post request
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class InPost extends Widget
{
    public function __invoke()
    {
        // debug support
        if ($this->config('debug') && $this->get('post')) {
            return true;
        }

        return 'POST' == strtoupper($this->server('REQUEST_METHOD'));
    }
}
