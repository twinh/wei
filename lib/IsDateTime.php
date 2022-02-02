<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a valid datetime with specific format
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsDateTime extends IsAnyDateTime
{
    protected $format = 'Y-m-d H:i:s';
}
