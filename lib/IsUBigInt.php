<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between 0 and 2^64-1(18,446,744,073,709,551,615) (8 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUBigInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = 0;

    /**
     * @var string
     */
    protected $max = '18446744073709551615';
}
