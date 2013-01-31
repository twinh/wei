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
class Callback extends AbstractRule
{
    protected $fn;
    
    public function __invoke($input, \Closure $fn = null)
    {
        $fn && $this->fn = $fn;
        
        return (bool) call_user_func($this->fn, $input, $this, $this->widget);
    }
}
