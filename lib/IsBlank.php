<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is blank
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsBlank extends BaseValidator
{
    protected $blankMessage = '%name% must be blank';

    protected $negativeMessage = '%name% must not be blank';

    /**
     * The invalid variable values
     *
     * @var array
     */
    protected $invalid = [null, '', false, []];

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (
            in_array($input, $this->invalid, true)
            || ($this->isString($input) && '' === trim($input))
        ) {
            return true;
        } else {
            $this->addError('blank');
            return false;
        }
    }
}
