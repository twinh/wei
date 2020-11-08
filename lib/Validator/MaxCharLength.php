<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the character length of input is lower than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MaxCharLength extends MaxLength
{
    protected $tooLongMessage = '%name% must be no more than %max% character(s)';

    protected $countByChars = true;
}
