<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid Hex color
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Color extends Regex
{
    protected $patternMessage = '%name% must be valid hex color, e.g. #FF0000';

    protected $negativeMessage = '%name% must not be valid hex color';

    protected $pattern = '/^#([0-9a-fA-F]{3}){1,2}$/';
}
