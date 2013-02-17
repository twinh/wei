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
class Regex extends AbstractValidator
{
    protected $patternMessage = '%name% must match against pattern "%pattern%"';
    
    protected $notMessage = '%name% must not match against pattern "%pattern%"';
    
    /**
     * The regex pattern
     * 
     * @var string
     */
    protected $pattern;
    
    public function __invoke($input, $pattern = null)
    {
        is_string($pattern) && $this->pattern = $pattern;
        
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (!preg_match($this->pattern, $input)) {
            $this->addError('pattern');
            return false;
        }
        
        return true;
    }
}
