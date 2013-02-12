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
class Length extends AbstractValidator
{
    protected $min;
    
    protected $max;
    
    protected $lengthMessage = '%name% must have a length between %min% and %max%';

    public function __invoke($input, $min = null, $max = null)
    {
        if (is_numeric($min) && is_numeric($max)) {
            $this->min = $min;
            $this->max = $max;
        }
        
        $len = static::getLength($input);
        
        if ($this->min > $len || $this->max < $len) {
            $this->addError('length');
            return false;
        }
        
        return true;
    }

    public static function getLength($input)
    {
        if (is_scalar($input)) {
            $fn = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
            return $fn($input);
        } elseif (is_array($input) || $input instanceof \Countable) {
            return count($input);
        } else {
            throw new UnexpectedTypeException($input, 'string, array, or \Countable');
        }
    }
}
