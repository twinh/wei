<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the character length of input is lower than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsMaxCharLength extends IsMaxLength
{
    protected $tooLongMessage = '%name% must be no more than %max% character(s)';

    protected $countByChars = true;
}
