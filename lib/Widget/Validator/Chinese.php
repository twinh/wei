<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * Check if the given string is a chinese word
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Chinese extends AbstractRule
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $value);
    }
}
