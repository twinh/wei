<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese mobile number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MobileCn extends Regex
{
    protected $patternMessage = '%name% must be valid mobile number';

    protected $pattern = '/^1[3458][\d]{9}$/';
}
