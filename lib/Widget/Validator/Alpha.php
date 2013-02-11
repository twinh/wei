<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Alpha extends Regex
{
    protected $patternMessage = '%name% must contain only letters (a-z)';
    
    protected $pattern = '/^([a-z]+)$/i';
}
