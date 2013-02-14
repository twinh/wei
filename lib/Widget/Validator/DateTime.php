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
    protected $formatMessage = '%name% is not a valid datetime, the format should be "%format%", eg: %example%';
    
    protected $format = 'Y-m-d H:i:s';
    
    public function __invoke($input, $format = null)
    {
        $format && $this->format = $format;
        
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        $date = Dt::createFromFormat($this->format, $input);
        
        if (!$date || $input != $date->format($this->format)) {
            $this->addError('format', array(
                'format' => $this->format,
                'example' => date($this->format)
            ));
            return false;
        }
        
        return true;
    }
}
