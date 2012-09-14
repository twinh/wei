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
 * @todo        remove global var $_SERVER
 */
class InAjax extends Widget
{
    public function __invoke()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
