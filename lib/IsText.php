<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;


/**
 * Check if the input is a string of 65535(64Kb-1) bytes or less
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsText extends IsString
{
    /**
     * @var int
     */
    protected $maxLength = 65535;
}
