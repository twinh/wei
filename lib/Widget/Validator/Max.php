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
class Max extends AbstractValidator
{
    protected $max;
    
    protected $lessThanMessage = '%name% must be less or equal than %max%';

    public function __invoke($input, $max = null)
    {
        $max && $this->max = $max;
        
        if ($this->max < $input) {
            $this->addError('lessThan', array(
                'max' => $this->max
            ));
            return false;
        }
        
        return true;
    }
}
