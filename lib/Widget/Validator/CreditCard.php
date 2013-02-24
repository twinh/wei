<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class CreditCard extends AbstractValidator
{
    protected $invalidMessage = '%name% must be valid credit card';
    
    protected $notMessage = '%name% must not be valid credit card';
    
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
            
        if (!$this->validateLuhn($input)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
    
    public function validateLuhn($input)
    {
        $len    = strlen($input);
        $sum    = 0;
        $offset = $len % 2;
        
        for ($i = 0; $i < $len; $i++) {
            if (0 == ($i + $offset) % 2) {
                $add = $input[$i] * 2;
                $sum += $add > 9 ? $add - 9 : $add;
            } else {
                $sum += $input[$i];
            }
        }

        return 0 == $sum % 10;
    }
}
