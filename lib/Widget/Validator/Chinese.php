<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input contains only Chinese characters
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Chinese extends Regex
{
    protected $patternMessage = '%name% must contain only Chinese characters';
    
    protected $negativeMessage = '%name% must not contain only Chinese characters';
    
    protected $pattern = '/^[\x{4e00}-\x{9fa5}]+$/u';
}
