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
 * Check if the input value is a valid datetime
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DateTime extends AbstractValidator
{
    /**
     * The error message for "format" property
     * 
     * @var string 
     */
    protected $formatMessage = '%name% must be a valid datetime, the format should be "%format%", eg: %example%';
    
    /**
     * The error message for "before" property
     *  
     * @var string
     */
    protected $tooLateMessage = '%name% must be earlier than %before%';
     
    /**
     * The error message for "after" property
     * 
     * @var string
     */
    protected $tooEarlyMessage = '%name% must be later than %after%';
    
    protected $notMessage = '%name% must not be a valid datetime';
    
    protected $format = 'Y-m-d H:i:s';
    
    protected $before;
    
    protected $after;
    
    protected $example;
    
    public function __invoke($input, $format = null)
    {
        $format && $this->setOption('format', $format);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        $date = Dt::createFromFormat($this->format, $input);
        
        if (!$date || $input != $date->format($this->format)) {
            $this->example = date($this->format);
            $this->addError('format');
            return false;
        }
        
        if ($this->before) {
            $before = Dt::createFromFormat($this->format, $this->before);
            if ($before < $date) {
                $this->addError('tooLate');
            }
        }
        
        if ($this->after) {
            $after = Dt::createFromFormat($this->format, $this->after);
            if ($after > $date) {
                $this->addError('tooEarly');
            }
        }
        
        return !$this->errors;
    }
}
