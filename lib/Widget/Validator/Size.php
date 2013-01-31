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
class Size extends AbstractRule
{
    protected $min;
    
    protected $max;
    
    protected $size;
    
    protected $message = 'This file size ({{size}}) must between {{ min }} and {{ max }}';

    public function __invoke($input, $min = null, $max = null)
    {
        if (is_numeric($min) && is_numeric($max)) {
            $this->min = $min;
            $this->max = $max;
        }
        
        $this->size = filesize($input);

        return $this->min <= $this->size && $this->max >= $this->size;
    }
}
