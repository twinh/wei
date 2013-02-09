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
class OneOf extends AbstractRule
{
    protected $rules = array();
    
    public function __invoke($input, array $rules = array())
    {
        $rules && $this->rules = $rules;
        foreach ($this->rules as $rule => $options) {
            if ($this->is->validateOne($rule, $input, $options)) {
                return true;
            }
        }
        return false;
    }
}
