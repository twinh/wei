<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the characters length (or size) of input is equals specified length
 * or in specified length range
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class CharLength extends Length
{
    protected $countByChars = true;
}