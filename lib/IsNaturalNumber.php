<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a natural number (integer that greater than or equals 0)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://en.wikipedia.org/wiki/Natural_number
 */
class IsNaturalNumber extends BaseValidator
{
    protected $invalidMessage = '%name% must be positive integer or zero';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (false !== filter_var($input, FILTER_VALIDATE_INT) && $input >= 0) {
            return true;
        } else {
            $this->addError('invalid');
            return false;
        }
    }
}
