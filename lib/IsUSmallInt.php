<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between 0 and 65,535 (2^16-1) (2 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUSmallInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = 0;

    /**
     * @var int
     */
    protected $max = 65535;
}
