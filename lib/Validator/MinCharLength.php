<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the character length of input is greater than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MinCharLength extends MinLength
{
    protected $tooShortMessage = '%name% must be at least %min% character(s)';

    protected $countByChars = true;
}
