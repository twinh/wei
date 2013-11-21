<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is a positive integer
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class PositiveInteger extends BaseValidator
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