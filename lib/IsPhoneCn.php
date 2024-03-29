<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Chinese phone number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsPhoneCn extends IsRegex
{
    protected $patternMessage = '%name% must be valid phone number';

    protected $negativeMessage = '%name% must not be phone number';

    protected $pattern = '/^(\d{3,4}-?)?\d{7,9}$/';
}
