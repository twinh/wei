<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is null
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Null extends AbstractValidator
{
    protected $notNullMessage = '%name% must be null';
    
    protected $negativeMessage = '%name% must not be null';
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!is_null($input)) {
            $this->addError('notNull');
            return false;
        }
        
        return true;
    }
}
