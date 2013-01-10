<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

use Widget\WidgetProvider;

/**
 * IsAlnum
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class IsAlnum extends WidgetProvider
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^([a-z0-9]+)$/i', $value);
    }
}
