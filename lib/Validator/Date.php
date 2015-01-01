<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is a valid date
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Date extends DateTime
{
    protected $formatMessage = '%name% must be a valid date, the format should be "%format%", e.g. %example%';

    protected $negativeMessage = '%name% must not be a valid date';

    protected $format = 'Y-m-d';
}
