<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is decimal
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Decimal extends AbstractValidator
{
    protected $invalidMessage = '%name% must be decimal';
    
    protected $negativeMessage = '%name% must not be decimal';
    
    /**
     * {@inheritdoc}
     */
    public function validate($input)
    {
        if (is_float($input) || (is_numeric($input) && count(explode('.', $input)) == 2)) {
            return true;
        }
        
        $this->addError('invalid');
        return false;
    }
}
