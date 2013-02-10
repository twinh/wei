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
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DateTime extends AbstractValidator
{
    protected $format = 'Y-m-d H:i:s';
    
    protected $example;
    
    protected $message = '%name% is not a valid datetime, the format should be "%format%", eg: %example%';

    public function __invoke($input, $format = null)
    {
        $format && $this->format = $format;
        
        $date = Dt::createFromFormat($this->format, $input);
        
        $this->example = date($this->format);

        return $date ? $input === $date->format($this->format) : false;
    }
}
