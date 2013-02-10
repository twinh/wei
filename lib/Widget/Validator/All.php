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
 * @property \Widget\Is $is The validator manager
 */
class All extends AbstractRule
{
    protected $typeMessage = '%name% must be of type array';
    
    protected $elementInvalidMessage = 'The %index% element of %name% is not valid';
    
    public function __invoke($input, array $rules)
    {
        if (!is_array($input) && !$input instanceof \Traversable) {
            $this->addError('type');
            return false;
        }

        $index = 1;
        foreach ($input as $element) {
            foreach ($rules as $rule => $options) {
                if (!$this->is->validateOne($rule, $element, $options)) {
                    $this->addError('elementInvalid', array(
                        'index' => $index
                    ));
                }
            }
            $index++;
        }
        
        return !$this->errors;
    }
}
