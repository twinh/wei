<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid by any of the rules
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class OneOf extends SomeOf
{
    protected $atLeastMessage = '%name% must be passed by at least one rule';

    protected $atLeast = 1;
}
