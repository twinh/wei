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
class Callback extends AbstractValidator
{
    protected $invalidMessage = '%name% is not valid';
    
    protected $fn;
    
    public function __invoke($input, \Closure $fn = null)
    {
        $fn && $this->fn = $fn;
        
        if (!call_user_func($this->fn, $input, $this, $this->widget)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
}
