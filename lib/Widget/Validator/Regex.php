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
class Regex extends AbstractRule
{
    protected $message = 'This value must match against pattern "%pattern%"';
    
    /**
     * The regex pattern
     * 
     * @var string
     */
    protected $pattern;
    
    public function __invoke($input, $pattern = null)
    {
        is_string($pattern) && $this->pattern = $pattern;

        return (bool) preg_match($this->pattern, $input);
    }
}
