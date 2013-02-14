<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

class AbstractLengthValidator extends AbstractValidator
{
    protected $notDetectdMessage = '%name%\'s length could not be detected';
    
    /**
     * Return the input value's length or false when could not detected
     * 
     * @param string|array|\Countable $input
     * @return int|false
     */
    public function getLength($input)
    {
        if (is_scalar($input)) {
            $fn = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
            return $fn($input);
        } elseif (is_array($input) || $input instanceof \Countable) {
            return count($input);
        } else {
            return false;
        }
    }
}
