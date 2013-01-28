<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * IsAlpha
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Blank extends AbstractRule
{
    protected $message = 'This value must be blank';
    
    public function __invoke($value)
    {
        return '' === trim($value);
    }
}
