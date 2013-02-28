<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is a valid time
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Time extends DateTime
{
    protected $formatMessage = '%name% must be a valid time, the format should be "%format%", eg: %example%';
    
    protected $notMessage = '%name% must not be a valid time';
    
    protected $format = 'H:i:s';
}
