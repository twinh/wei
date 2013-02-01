<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\Exception;

/**
 * Check if the data is the given type
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Type extends AbstractRule
{
    protected $message = 'This value must be of type {{ type }}';
    
    protected $type;
    
    public function __invoke($value, $type = null)
    {
        $type && $this->type = $type;
        
        if (function_exists($fn = 'is_' . $type)) {
            return $fn($value);
        } elseif (function_exists($fn = 'ctype_' . $type)) {
            return $fn($value);
        } else {
            throw new Exception(sprintf('Unknow type "%s"', $type));
        }
    }
}
