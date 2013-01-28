<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\UnexpectedTypeException;

/**
 * The default logger for widget, which is base on the Monolog!
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class All extends AbstractRule
{
    public function __invoke($data, array $rules)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or \Traversable');
        }
        
        foreach ($data as $element) {
            foreach ($rules as $rule => $options) {
                if (!$this->is->validateOne($rule, $element, $options)) {
                    return false;
                }
            }
        }
        
        return true;
    }
}
