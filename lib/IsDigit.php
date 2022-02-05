<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input contains only digits (0-9)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsDigit extends IsRegex
{
    protected $patternMessage = '%name% must contain only digits (0-9)';

    protected $negativeMessage = '%name% must not contain only digits (0-9)';

    protected $pattern = '/^([0-9]+)$/';
}
