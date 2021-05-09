<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between 0 and 4,294,967,295 (2^32-1) (4 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUDefaultInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = 0;

    /**
     * @var int
     */
    protected $max = 4294967295;
}
