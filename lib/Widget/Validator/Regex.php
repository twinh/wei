<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Regex
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Regex extends AbstractRule
{
    /**
     * The regex pattern
     * 
     * @var string
     */
    protected $pattern;
    
    public function __invoke($value, $pattern = null)
    {
        is_string($pattern) && $this->pattern = $pattern;

        return (bool) preg_match($this->pattern, $value);
    }
}
