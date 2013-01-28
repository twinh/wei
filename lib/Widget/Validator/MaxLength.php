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
class MaxLength extends Length
{
    protected $limit;
    
    protected $message = 'This value must have a length lower than {{ max }}';

    public function __invoke($data, $options = array())
    {
        if (is_int($options)) {
            $this->limit = $options;
        } else {
            $this->option($options);
        }
        
        if ($this->limit >= $this->getLength($data)) {
            return false;
        } else {
            return true;
        }
    }
}
