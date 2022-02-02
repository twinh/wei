<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Ignore the remaining rules of current field if input value is empty string or null
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @internal    This is not a really validator, may be change in the future
 */
final class IsAllowEmpty extends BaseValidator
{
    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (in_array($input, ['', null], true)) {
            $this->validator->skipNextRules();
        }
        return true;
    }
}
