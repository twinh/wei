<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a unsigned number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUNumber extends IsNumber
{
    /**
     * {@inheritdoc}
     */
    public const BASIC_TYPE = true;

    /**
     * {@inheritdoc}
     */
    protected $unsigned = true;
}
