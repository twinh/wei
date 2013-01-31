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
class AllOf extends AbstractRule
{
    public function __invoke($data, array $rules)
    {
        foreach ($rules as $rule => $options) {
            if (!$this->is->validateOne($rule, $data, $options)) {
                return false;
            }
        }
        return true;
    }
}
