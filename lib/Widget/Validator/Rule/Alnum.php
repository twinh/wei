<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * IsAlnum
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Alnum extends AbstractRule
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^([a-z0-9]+)$/i', $value);
    }
}
