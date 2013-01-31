<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the data contains only double byte characters
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DoubleByte extends Regex
{
    protected $message = 'This value must contain only double byte characters';
    
    protected $pattern = '/^[^\x00-xff]+$/';
}
