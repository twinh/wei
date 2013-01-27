<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use DateTime as Dt;

/**
 * The date validate rule
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DateTime extends AbstractRule
{
    protected $format = 'Y-m-d H:i:s';
    
    protected $message = 'This value is not a valid datetime, the format should be {{ format }}';

    public function __invoke($value)
    {
        $date = Dt::createFromFormat($this->format, $value);

        return $date ? $value === $date->format($this->format) : false;
    }
}
