<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * range
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Range extends AbstractRule
{
    public function __invoke($data, $min, $max)
    {
        return $min <= $data && $max >= $data;
    }
}
