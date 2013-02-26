<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Callback extends AbstractValidator
{
    protected $invalidMessage = '%name% is not valid';
    
    /**
     * The callback to validate the input value
     * 
     * @var callback
     */
    protected $fn;
    
    /**
     * Invoker
     * 
     * @param mixed $input The input value
     * @param \Closure|null $fn  The callback to validate the input value
     * @param string|null $message The custom invalid message
     * @return bool
     */
    public function __invoke($input, \Closure $fn = null, $message = null)
    {
        $fn && $this->fn = $fn;
        $message && $this->invalidMessage = $message;
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!call_user_func($this->fn, $input, $this, $this->widget)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
}
