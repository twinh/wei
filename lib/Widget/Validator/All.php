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
class All extends AbstractValidator
{
    protected $typeMessage = '%name% must be of type array';
    
    protected $itemName = '%name%\'s %index% item';
    
    public function __invoke($input, array $rules)
    {
        if (!is_array($input) && !$input instanceof \Traversable) {
            $this->addError('type');
            return false;
        }

        $index = 1;
        foreach ($input as $item) {
            foreach ($rules as $rule => $options) {
                if (!$this->is->validateOne($rule, $item, $options, $validator)) {
                    foreach ($validator->getErrors() as $name => $error) {
                        $this->addError($rule . '.' . $name . '.' . $index, array(
                            'name' => $this->itemName,
                            'index' => $index
                        ) + $error[1], $error[0]);
                    }
                }
            }
            $index++;
        }
        
        return !$this->errors;
    }
}
