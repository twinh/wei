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
 * IsPostcode
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Postcode extends WidgetProvider
{
    public function __invoke($data)
    {
        return (bool) preg_match('/^[1-9][\d]{5}$/', $data);
    }
}
