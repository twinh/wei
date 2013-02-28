<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese postcode
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Postcode extends Regex
{
    protected $patternMessage = '%name% must be six length of digit';
    
    protected $notMessage = '%name% must not be postcode';
    
    protected $pattern = '/^[1-9][\d]{5}$/';
}
