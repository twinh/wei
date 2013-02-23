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
class Url extends AbstractValidator
{
    protected $invalidMessage = '%name% must be valid URL';
    
    protected $notMessage = '%name% must not be URL';
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!filter_var($input, FILTER_VALIDATE_URL)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
}
