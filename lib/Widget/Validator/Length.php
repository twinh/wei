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
}
