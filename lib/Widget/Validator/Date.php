<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

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

    protected $message = 'This value is not a valid date, the format should be {{ format }}';
    
    public function __invoke($value, $format = null)
    {
        $format && $format = $this->format;

        $date = DateTime::createFromFormat($this->format, $value);

        return $date ? $value === $date->format($this->format) : false;
    }
}
