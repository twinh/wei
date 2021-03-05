<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a float value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsFloat extends BaseValidator
{
    protected $notFloatMessage = '%name% must be a float value';

    protected $negativeMessage = '%name% must not be a float value';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_float(filter_var($input, FILTER_VALIDATE_FLOAT))
            // `true` will convert to `1.0`
            || true === $input
        ) {
            $this->addError('notFloat');
            return false;
        }

        return true;
    }
}
