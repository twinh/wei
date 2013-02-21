<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Null extends AbstractValidator
{
    protected $notNullMessage = '%name% must be null';
    
    protected $notMessage = '%name% must not be null';
    
    public function __invoke($input)
    {
        return $this->isValid($input);
    }
    
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
