<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a bool value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsBoolable extends BaseValidator
{
    protected $notBoolMessage = '%name% must be a bool value';

    protected $negativeMessage = '%name% must not be a bool value';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (is_bool($input)) {
            return true;
        }

        if (null === filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            $this->addError('notBool');
            return false;
        }
        return true;
    }
}
