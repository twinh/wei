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
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Is $is The validator manager
 */
class All extends AbstractRule
{
    public function __invoke($input, array $rules)
    {
        if (!is_array($input) && !$input instanceof \Traversable) {
            throw new UnexpectedTypeException($input, 'array or \Traversable');
        }
        
        foreach ($input as $element) {
            foreach ($rules as $rule => $options) {
                if (!$this->is->validateOne($rule, $element, $options)) {
                    return false;
                }
            }
        }
        
        return true;
    }
}
