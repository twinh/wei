<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is a unsigned number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class UNumber extends Number
{
    /**
     * {@inheritdoc}
     */
    protected $unsigned = true;
}
