<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid phone number, contains only digit, +, - and spaces
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsPhone extends IsRegex
{
    protected $patternMessage = '%name% must be valid phone number';

    protected $negativeMessage = '%name% must not be phone number';

    protected $pattern = '/^[\+]?[0-9- ]+$/';
}
