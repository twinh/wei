<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Length extends AbstractRule
{
    protected $min;
    
    protected $max;
    
    protected $minMessage = 'This value must have a length greater than {{ min }}';
    
    protected $maxMessage = 'This value must have a length lower than {{ max }}';
    
    protected $message = 'This value must have a length between {{ min }} and {{ max }}';

    public function __invoke($data, $options = array())
    {
        if (is_int($options)) {
            $this->max = $this->min = $options;
        } else {
            $this->option($options);
        }
        
        $fn = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
        $len = $fn($data);
        
        if ($this->max === $this->min) {
            return $len === $this->max;
        } else {
            return $this->min <= $len && $this->max >= $len;
        }
    }
    
    public function getMessage()
    {
        if (!is_null($this->min) && $this->max) {
            $message = $this->message;
        } elseif ($this->min) {
            $message = $this->minMessage;
        } elseif ($this->max) {
            $message = $this->maxMessage;
        } else {
            $message = $this->message;
        }
        
        return $message;
    }
}
