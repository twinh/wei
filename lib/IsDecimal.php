<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is decimal
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsDecimal extends BaseValidator
{
    /**
     * {@inheritdoc}
     */
    public const BASIC_TYPE = true;

    protected $invalidMessage = '%name% must be decimal';

    protected $negativeMessage = '%name% must not be decimal';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (is_float($input) || (is_numeric($input) && 2 == count(explode('.', $input)))) {
            return true;
        }

        $this->addError('invalid');
        return false;
    }
}
