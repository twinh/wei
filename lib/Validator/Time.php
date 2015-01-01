<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is a valid time
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Time extends DateTime
{
    protected $formatMessage = '%name% must be a valid time, the format should be "%format%", e.g. %example%';

    protected $negativeMessage = '%name% must not be a valid time';

    protected $format = 'H:i:s';
}
