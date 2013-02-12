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
class MinLength extends AbstractValidator
{
    protected $minMessage = '%name% must have a length greater than %min%';
    
    protected $minItemMessage = '%name% must contain at least %min% item(s)';
    
    protected $min;

    public function __invoke($input, $min = null)
    {
        $min && $this->min = $min;
        
        if ($this->min > Length::getLength($input)) {
            $this->addError(is_scalar($input) ? 'min' : 'minItem', array(
                'min' => $min
            ));
            return false;
        }
        
        return true;
    }
}
