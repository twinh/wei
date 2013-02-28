<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input contains only Chinese characters
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Chinese extends Regex
{
    protected $patternMessage = '%name% must contain only Chinese characters';
    
    protected $notMessage = '%name% must not contain only Chinese characters';
    
    protected $pattern = '/^[\x{4e00}-\x{9fa5}]+$/u';
}
