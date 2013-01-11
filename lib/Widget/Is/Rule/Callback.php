<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

/**
 * Callback
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Callback extends AbstractRule
{
    public function __invoke($data, $fn)
    {
        return (bool) call_user_func($fn, $data);
    }
}
