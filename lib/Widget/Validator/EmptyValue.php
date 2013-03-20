<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is empty
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class EmptyValue extends AbstractValidator
{
    protected $emptyMessage = '%name% must be empty';
    
    protected $negativeMessage = '%name% must not be empty';
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!empty($input)) {
            $this->addError('empty');
            return false;
        }
        
        return true;
    }
}
