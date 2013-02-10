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
class Range extends AbstractRule
{
    protected $message = '%name% must between %min% and %max%';
    
    protected $min;
    
    protected $max;
    
    public function __invoke($input, $min = null, $max = null)
    {
        if ($min && $max) {
            $this->min = $min;
            $this->max = $max;
        }

        return $this->min <= $input && $this->max >= $input;
    }
}
