<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input contains letters (a-z) and digits (0-9)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsAlnum extends IsRegex
{
    protected $patternMessage = '%name% must contain letters (a-z) and digits (0-9)';

    protected $negativeMessage = '%name% must not contain letters (a-z) or digits (0-9)';

    protected $pattern = '/^([a-z0-9]+)$/i';
}
