<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is blank
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Blank extends AbstractValidator
{
    protected $blankMessage = '%name% must be blank';
    
    protected $negativeMessage = '%name% must not be blank';
        
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if ($this->isString($input)) {
            $result = '' === trim($input);
        } else {
            $result = !empty($input);
        }
        
        if (!$result) {
            $this->addError('blank');
        }
        
        return $result;
    }
}
