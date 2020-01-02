<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input contains only letters (a-z)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Alpha extends Regex
{
    protected $patternMessage = '%name% must contain only letters (a-z)';

    protected $pattern = '/^([a-z]+)$/i';
}
