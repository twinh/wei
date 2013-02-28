<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid UUID(v4)
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Uuid extends Regex
{
    protected $patternMessage = '%name% must be valid UUID';
    
    protected $notMessage = '%name% must not be valid UUID';
    
    protected $pattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
}
