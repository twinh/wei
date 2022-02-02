<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input contains only letters (a-z)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsAlpha extends IsRegex
{
    protected $patternMessage = '%name% must contain only letters (a-z)';

    protected $pattern = '/^([a-z]+)$/i';
}
