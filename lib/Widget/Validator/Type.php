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
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Type extends AbstractValidator
{
    protected $typeMessage = '%name% must be of type %type%';
    
    protected $type;
    
    public function __invoke($input, $type = null)
    {
        $type && $this->type = $type;
        
        if (function_exists($fn = 'is_' . $this->type)) {
            $result = $fn($input);
        } elseif (function_exists($fn = 'ctype_' . $this->type)) {
            $result = $fn($input);
        } else {
            throw new Exception(sprintf('Unknow type "%s"', $this->type));
        }
        
        if (!$result) {
            $this->addError('type', array(
                'type' => $this->type
            ));
        }
        
        return $result;
    }
}
