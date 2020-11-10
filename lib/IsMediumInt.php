<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between -8388608(-2^23) and 8388607(2^23-1) (3 Bytes)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsMediumInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = -8388608;

    /**
     * @var int
     */
    protected $max = 8388607;
}
