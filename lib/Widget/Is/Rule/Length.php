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
 * IsLength
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Length extends WidgetProvider
{
    public function __invoke($data, $min, $max = null)
    {
        $len = strlen($data);
        if (0 === $max) {
            return $len >= $min;
        } elseif (!$max) {
            return $len == $min;
        } else {
            return $min <= $len && $max >= $len;
        }
    }
}
