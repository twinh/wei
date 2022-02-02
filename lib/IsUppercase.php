<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is uppercase
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUppercase extends BaseValidator
{
    protected $invalidMessage = '%name% must be uppercase';

    protected $negativeMessage = '%name% must not be uppercase';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (mb_strtoupper($input, mb_detect_encoding($input, mb_detect_order(), true)) != $input) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
}
