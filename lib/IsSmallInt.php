<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between -32768(-2^15) and 32767(2^15-1) (2 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsSmallInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = -32768;

    /**
     * @var int
     */
    protected $max = 32767;
}
