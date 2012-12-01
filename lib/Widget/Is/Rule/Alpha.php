<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

use Widget\WidgetProvider;

/**
 * IsAlpha
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Alpha extends WidgetProvider
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^([a-z]+)$/i', $value);
    }
}
