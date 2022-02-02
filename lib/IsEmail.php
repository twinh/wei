<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid email address
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsEmail extends BaseValidator
{
    protected $formatMessage = '%name% must be valid email address';

    protected $negativeMessage = '%name% must not be an email address';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!filter_var($input, \FILTER_VALIDATE_EMAIL)) {
            $this->addError('format');
            return false;
        }

        return true;
    }
}
