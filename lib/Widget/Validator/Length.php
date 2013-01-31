<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\UnexpectedTypeException;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Length extends AbstractRule
{
    protected $min;
    
    protected $max;
    
    protected $message = 'This value must have a length between {{ min }} and {{ max }}';

    public function __invoke($data, $min = null, $max = null)
    {
        // isLength($data, $min, $max)
        if (is_int($min) && is_int($max)) {
            $this->option(array(
                'min' => $min,
                'max' => $max
            ));
        }
        
        $len = static::getLength($data);
        
        if ($this->max === $this->min) {
            return $len === $this->max;
        } else {
            return $this->min <= $len && $this->max >= $len;
        }
    }

    public static function getLength($value)
    {
        if (is_scalar($value)) {
            $fn = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
            return $fn($value);
        } elseif (is_array($value) || $value instanceof \Countable) {
            return count($value);
        } else {
            throw new UnexpectedTypeException($value, 'string, array, or \Countable');
        }
    }
}
