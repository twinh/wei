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
class Alpha extends AbstractRule
{
    protected $message = 'This value must contain only letters (a-z)';
    
    public function __invoke($value)
    {
        return (bool) preg_match('/^([a-z]+)$/i', $value);
    }
}
