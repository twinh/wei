<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input contains only letters
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Alpha extends Regex
{
    protected $patternMessage = '%name% must contain only letters (a-z)';
    
    protected $pattern = '/^([a-z]+)$/i';
}
