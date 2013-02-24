<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Config
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Config extends AbstractWidget
{
    public function __invoke()
    {
        return call_user_func_array(array($this->widget, 'config'), func_get_args());
    }
}
