<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * IsEqual
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Equal extends AbstractRule
{
    /**
     * @param boolean $strict
     */
    public function __invoke($value, $mixed = null, $strict = false)
    {
        return $strict ? $value === $mixed : $value == $mixed;
    }
}
