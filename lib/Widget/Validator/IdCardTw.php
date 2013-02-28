<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input value is valid Taiwan identity card
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class IdCardTw extends AbstractValidator
{
    protected $invalidMessage = '%name% must be valid Taiwan identity card';
    
    protected $notMessage = '%name% must not be valid Taiwan identity card';
        
    /**
     * {@inheritdoc}
     */
    protected function validate($input) 
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        
        if (10 != strlen($input)) {
            $this->addError('invalid');
            return false;
        }
        
        $input = strtoupper($input);
        
        // Validate the city letter, should be A-Z
        $first = ord($input[0]);
        if ($first <  65 || $first > 90) {
            $this->addError('invalid');
            return false;
        }
        
        // Validate the gender
        if ($input[1] != '1' && $input[1] != '2') {
            $this->addError('invalid');
            return false;
        }
        
        
        list($x1, $x2) = str_split((string)($first - 55));
        $sum = $x1 + 9 * $x2;
        for ($i = 1, $j = 8; $i < 9; $i++, $j--) {
            $sum += $input[$i] * $i;
        }
        
        if (!($sum % 10)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
}
