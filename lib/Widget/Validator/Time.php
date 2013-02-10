<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Time extends DateTime
{
    protected $format = 'H:i:s';
    
    protected $message = '%name% is not a valid time, the format should be "%format%", eg: %example%';
}
