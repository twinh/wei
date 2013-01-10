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
 * Regex
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Regex extends WidgetProvider
{
    public function __invoke($value, $x)
    {
        return (bool) preg_match($x, $value);
    }
}
