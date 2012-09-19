<?php
/**
 * Qwin Framework
 * 
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Config
 * 
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Config extends Widget
{
    public function __invoke()
    {
        $args = func_get_args();
        return call_user_func_array(array($this->qwin, 'config'), $args);
    }
}
