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
    
    public function __invoke($data, $options = array())
    {
        if ($options) {
            // ($data, function(){})
            if ($options instanceof \Closure) {
                $this->fn = $options;
            // ($data, array());
            } elseif (is_array($options)) {
                $this->option($options);
            } else {
                throw new \InvalidArgumentException('Parameter 2 should be instance of \Closure or array');
            }
        }
        
        if (!is_callable($this->fn)) {
            throw new \InvalidArgumentException('Property $fn should be a valid callback');
        }
        
        return (bool) call_user_func($this->fn, $data, $this, $this->widget);
    }
}
