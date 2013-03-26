<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese mobile number
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class MobileCn extends Regex
{
    protected $patternMessage = '%name% must be valid mobile number';
    
    protected $pattern = '/^1[358][\d]{9}$/';
}
