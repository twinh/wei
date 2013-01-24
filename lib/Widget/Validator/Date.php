<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

use DateTime;

/**
 * The date validate rule
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Date extends AbstractRule
{
    protected $format = 'Y-m-d';

    public function __invoke($value)
    {
        $date = DateTime::createFromFormat($this->format, $value);

        return $date ? $value === $date->format($this->format) : false;
    }
}
