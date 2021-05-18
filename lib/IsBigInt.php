<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between -2^63(-9,223,372,036,854,775,808) and 2^63-1(9,223,372,036,854,775,807)
 * (8 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsBigInt extends IsInt
{
    /**
     * @var string
     */
    protected $min = '-9223372036854775808';

    /**
     * @var string
     */
    protected $max = '9223372036854775807';
}
