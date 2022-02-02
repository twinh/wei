<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a valid time with specific format
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsTime extends IsAnyDateTime
{
    protected $formatMessage = '%name% must be a valid time, the format should be "%format%", e.g. %example%';

    protected $negativeMessage = '%name% must not be a valid time';

    protected $format = 'H:i:s';
}
