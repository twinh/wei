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
class Min extends AbstractValidator
{
    protected $min;
    
    protected $minMessage = '%name% must be greater or equal than %min%';

    public function __invoke($input, $min = null)
    {
        $min && $this->min = $min;
        
        if ($this->min > $input) {
            $this->addError('min', array(
                'min' => $this->min
            ));
            return false;
        }
        
        return true;
    }
}
