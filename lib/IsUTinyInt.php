<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between 0 and 255 (2^8-1) (1 Byte)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUTinyInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = 0;

    /**
     * @var int
     */
    protected $max = 255;
}
