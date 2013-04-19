<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is lowsercase
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Lowercase extends AbstractValidator
{
    protected $invalidMessage = '%name% must be lowercase';
    
    protected $negativeMessage = '%name% must not be lowercase';
    
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        if (mb_strtolower($input, mb_detect_encoding($input)) != $input) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
}
