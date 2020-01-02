<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input contains only double characters
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class DoubleByte extends Regex
{
    protected $patternMessage = '%name% must contain only double byte characters';

    protected $negativeMessage = '%name% must not contain only double byte characters';

    protected $pattern = '/^[^\x00-xff]+$/';
}
