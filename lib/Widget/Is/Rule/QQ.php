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
 * IsQQ
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class QQ extends WidgetProvider
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^[1-9][\d]{4,9}$/', $value);
    }
}
