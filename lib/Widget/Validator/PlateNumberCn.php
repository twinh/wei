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
class PlateNumberCn extends Regex
{
    protected $pattern = '/^[\x{4e00}-\x{9fa5}]{1}[A-Z]{1}[A-Z0-9]{5}$/ui';
}
