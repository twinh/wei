<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between 0 and 16,777,215 (2^24-1) (3 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUMediumInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = 0;

    /**
     * @var int
     */
    protected $max = 16777215;
}
