<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Macau identity card
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link http://zh.wikipedia.org/wiki/%E6%BE%B3%E9%96%80%E5%B1%85%E6%B0%91%E8%BA%AB%E4%BB%BD%E8%AD%89
 */
class IsIdCardMo extends IsRegex
{
    protected $invalidMessage = '%name% is incorrect';

    protected $negativeMessage = '%name% must not be an identity card number';

    protected $pattern = '/^[1|5|7][\d]{7}$/';
}
