<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if data valid by callback
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Callback extends AbstractRule
{
    protected $fn;
    
    public function __invoke($data, \Closure $fn = null)
    {
        $fn && $this->fn = $fn;
        
        return (bool) call_user_func($this->fn, $data, $this, $this->widget);
    }
}
