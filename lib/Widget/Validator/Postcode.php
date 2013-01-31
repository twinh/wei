<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * The chinese postcode validate rule
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Postcode extends Regex
{
    protected $message = 'This value must be six length of digit';
    
    protected $pattern = '/^[1-9][\d]{5}$/';
}
