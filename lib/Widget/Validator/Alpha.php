<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input contains only letters (a-z)
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Alpha extends Regex
{
    protected $patternMessage = '%name% must contain only letters (a-z)';
    
    protected $pattern = '/^([a-z]+)$/i';
}
