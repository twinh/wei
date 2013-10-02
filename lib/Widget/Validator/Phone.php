<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid phone number, contains only digit, +, - and spaces
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Phone extends Regex
{
    protected $patternMessage = '%name% must be valid phone number';

    protected $negativeMessage = '%name% must not be phone number';

    protected $pattern = '/^[\+]?[0-9- ]+$/';
}
