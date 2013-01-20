<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * The default logger for widget, which is base on the Monolog!
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class OneOf extends AbstractRule
{
    public function __invoke($data, array $rules)
    {
        foreach ($rules as $rule => $options) {
            if ($this->is->validateOne($rule, $data, $options)) {
                return true;
            }
        }
        return false;
    }
}
