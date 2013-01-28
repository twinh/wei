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
class MaxLength extends AbstractRule
{
    protected $limit;
    
    protected $message = 'This value must have a length lower than {{ limit }}';

    public function __invoke($data, $options = array())
    {
        if (is_int($options)) {
            $this->limit = $options;
        } else {
            $this->option($options);
        }
        
        if ($this->limit >= Length::getLength($data)) {
            return false;
        } else {
            return true;
        }
    }
}
