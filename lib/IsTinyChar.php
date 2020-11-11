<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a string of 255 characters or less
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsTinyChar extends IsChar
{
    /**
     * @var int
     */
    protected $maxLength = 255;
}
