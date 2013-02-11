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
class QQ extends Regex
{
    protected $patternMessage = '%name% must be valid QQ number';
    
    protected $pattern = '/^[1-9][\d]{4,9}$/';
}
