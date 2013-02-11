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
class Alnum extends Regex
{
    protected $patternMessage = '%name% must contain letters (a-z) and digits (0-9)';
    
    protected $pattern = '/^([a-z0-9]+)$/i';
}
