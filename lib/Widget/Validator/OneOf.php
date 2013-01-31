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
class OneOf extends AbstractRule
{
    public function __invoke($input, array $rules)
    {
        foreach ($rules as $rule => $options) {
            if ($this->is->validateOne($rule, $input, $options)) {
                return true;
            }
        }
        return false;
    }
}
