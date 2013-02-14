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
class MaxLength extends AbstractLengthValidator
{
    protected $maxMessage = '%name% must have a length lower than %max%';
    
    protected $maxItemMessage = '%name% must contain no more than %max% items';
    
    protected $max;
    
    public function __invoke($input, $max = null)
    {
        $max && $this->max = $max;
        
        if (false === ($len = $this->getLength($input))) {
            $this->addError('notDetectd');
            return false;
        }
        
        if ($this->max < $len) {
            $this->addError(is_scalar($input) ? 'max' : 'maxItem', array(
                'max' => $max
            ));
            return false;
        }
        
        return true;
    }
}
