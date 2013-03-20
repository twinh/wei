<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is uppercase
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Uppercase extends AbstractValidator
{
    protected $invalidMessage = '%name% must be uppercase';
    
    protected $negativeMessage = '%name% must not be uppercase';
    
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        if (mb_strtoupper($input, mb_detect_encoding($input)) != $input) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
}
