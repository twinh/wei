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
class Min extends AbstractRule
{
    protected $limit;
    
    protected $message = 'This value must be greater or equal than {{ limit }}';

    public function __invoke($data, $options = array())
    {
        if (!is_array($options)) {
            $this->limit = $options;
        } else {
            $this->option($options);
        }
        
        if ($this->limit <= $data) {
            return true;
        } else {
            return false;
        }
    }
}
