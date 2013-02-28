<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese phone number
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Phone extends Regex
{
    protected $patternMessage = '%name% must be valid phone number';
    
    protected $notMessage = '%name% must not be phone number';
    
    protected $pattern = '/^(\d{3,4}-?)?\d{7,9}$/';
}
