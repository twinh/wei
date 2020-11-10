<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between -2^63(-9,223,372,036,854,775,808) and 2^64-1(9,223,372,036,854,775,807) (8 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsBigInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = -2 ^ 63;

    /**
     * @var int
     */
    protected $max = 2 ^ 64 - 1;
}
