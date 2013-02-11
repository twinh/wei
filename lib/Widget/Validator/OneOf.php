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
class OneOf extends AbstractGroupValidator
{
    protected $noRulePassedMessage = '%name% must be passed by at least one rule';
    
    protected $rules = array();
    
    public function __invoke($input, array $rules = array())
    {
        $rules && $this->rules = $rules;

        // Adds no rule passed error at first, if any rule is passed, the error will be removed
        $this->addError('noRulePassed', array(
            'count' => 1
        ));

        $validator = null;
        foreach ($this->rules as $rule => $options) {
            if ($this->is->validateOne($rule, $input, $options, $validator)) {
                // Removes all error messages
                $this->errors = array();
                return true;
            } else {
                foreach ($validator->getErrors() as $name => $error) {
                    $this->addError($rule . '.' . $name, $error[1], $error[0]);
                }
            }
        }

        return false;
    }
}
