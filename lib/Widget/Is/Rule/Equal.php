<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

use Widget\WidgetProvider;

/**
 * IsEqual
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Equal extends WidgetProvider
{
    public function __invoke($value, $mixed = null, $strict = false)
    {
        return $strict ? $value === $mixed : $value == $mixed;
    }
}
