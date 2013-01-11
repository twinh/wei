<?php

/**
 * Widget Framework
 * 
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

/**
 * Check if the given string is a chinese word
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */

class Chinese
{
    public function __invoke($value)
    {
        // todo
        return (bool) preg_match('/^[\u4e00-\u9fa5]+$/', $value);
    }
}
