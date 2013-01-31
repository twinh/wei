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
class Equal extends AbstractRule
{
    protected $message = 'This value must be equals {{ value }}';
    
    /**
     * @param boolean $strict
     */
    public function __invoke($value, $mixed = null, $strict = false)
    {
        return $strict ? $value === $mixed : $value == $mixed;
    }
}
