<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * IsQQ
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class QQ extends AbstractRule
{
    protected $message = 'This value must be valid QQ number';
    
    public function __invoke($value)
    {
        return (bool) preg_match('/^[1-9][\d]{4,9}$/', $value);
    }
}
