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
class MaxLength extends AbstractValidator
{
    protected $max;
    
    protected $maxMessage = '%name% must have a length lower than %max%';
    
    protected $maxItemMessage = '%name% must contain no more than %max% items';

    public function __invoke($input, $max = null)
    {
        $max && $this->max = $max;
        
        if ($this->max < Length::getLength($input)) {
            $this->addError(is_scalar($input) ? 'max' : 'maxItem', array(
                'max' => $max
            ));
            return false;
        }
        
        return true;
    }
}
