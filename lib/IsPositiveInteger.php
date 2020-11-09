<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a positive integer (integer that greater than 0)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsPositiveInteger extends BaseValidator
{
    protected $invalidMessage = '%name% must be positive integer';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (false !== filter_var($input, FILTER_VALIDATE_INT) && $input > 0) {
            return true;
        } else {
            $this->addError('invalid');
            return false;
        }
    }
}
