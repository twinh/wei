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
 * @property    \Widget\Is $is The validator manager
 */
class SomeOf extends AbstractGroupValidator
{
    protected $atLeastMessage = '%name% must be passed by at least %left% of %count% rules';
    
    protected $rules = array();
    
    protected $atLeast;
    
    public function __invoke($input, array $rules = array(), $atLeast = null)
    {
        $atLeast && $this->atLeast = $atLeast;
        $rules && $this->rules = $rules;
        
        // Adds "atLeast" error at first, make sure this error at the top of 
        // strack, if any rule is passed, the error will be removed
        $this->addError('atLeast');

        $passed = 0;
        $validator = null;
        foreach ($this->rules as $rule => $options) {
            if ($this->is->validateOne($rule, $input, $options, $validator)) {
                $passed++;
                if ($passed >= $this->atLeast) {
                    // Removes all error messages
                    $this->errors = array();
                    return true;
                }
            } else {
                $validator->setName($this->name);
                $this->validators[$rule] = $validator;
            }
        }
        
        $this->count = count($rules) - $passed;
        $this->left = $this->atLeast - $passed;
        
        return false;
    }
}
