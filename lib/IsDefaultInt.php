<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between -2147483648(-2^31) and 2147483647(2^31-1) (4 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsDefaultInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = -2147483648;

    /**
     * @var int
     */
    protected $max = 2147483647;
}
