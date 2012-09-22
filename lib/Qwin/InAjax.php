<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Check if in ajax request
 * 
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class InAjax extends Widget
{
    public function __invoke()
    {
        // debug support
        if ($this->config('debug') && $this->get('ajax')) {
            return true;
        }
        
        return 'xmlhttprequest' == strtolower($this->server('HTTP_X_REQUESTED_WITH'));
    }
}
