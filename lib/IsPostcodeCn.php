<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Chinese postcode
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsPostcodeCn extends IsRegex
{
    protected $patternMessage = '%name% must be six length of digit';

    protected $negativeMessage = '%name% must not be postcode';

    protected $pattern = '/^[1-9][\d]{5}$/';
}
