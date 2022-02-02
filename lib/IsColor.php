<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Hex color
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsColor extends IsRegex
{
    protected $patternMessage = '%name% must be valid hex color, e.g. #FF0000';

    protected $negativeMessage = '%name% must not be valid hex color';

    protected $pattern = '/^#([0-9a-fA-F]{3}){1,2}$/';
}
