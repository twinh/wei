<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid Chinese mobile number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MobileCn extends Regex
{
    protected $patternMessage = '%name% must be valid mobile number';

    protected $pattern = '/^1[3456789][\d]{9}$/';
}
