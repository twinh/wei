<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is a valid date
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Date extends DateTime
{
    protected $formatMessage = '%name% must be a valid date, the format should be "%format%", e.g. %example%';
    
    protected $notMessage = '%name% must not be a valid date';
    
    protected $format = 'Y-m-d';
}
