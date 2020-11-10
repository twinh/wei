<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is int value and between -128(-2^7) and 127(2^7-1) (1 Byte)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsTinyInt extends IsInt
{
    /**
     * @var int
     */
    protected $min = -128;

    /**
     * @var int
     */
    protected $max = 127;
}
