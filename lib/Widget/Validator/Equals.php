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
class Equals extends AbstractRule
{
    protected $message = 'This value must be equals {{ value }}';
    
    protected $equals;
    
    protected $strict = false;
    
    public function __invoke($input, $equals = null, $strict = null)
    {
        // Sets $this->equals only when the second argument provided
        func_num_args() > 1 && $this->equals = $equals;
        is_bool($strict) && $this->strict = $strict;
        
        return $this->strict ? $input === $this->equals : $input == $this->equals;
    }
}
