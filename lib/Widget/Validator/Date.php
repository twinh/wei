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
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Date extends AbstractRule
{
    protected $format = 'Y-m-d';

    protected $message = 'This value is not a valid date, the format should be {{ format }}';
    
    public function __invoke($input, $format = null)
    {
        $format && $format = $this->format;

        $date = DateTime::createFromFormat($this->format, $input);

        return $date ? $input === $date->format($this->format) : false;
    }
}
