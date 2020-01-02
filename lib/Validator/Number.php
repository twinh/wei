<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Number extends BaseValidator
{
    protected $notNumberMessage = '%name% must be valid number';

    protected $negativeMessage = '%name% must not be number';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_numeric($input)) {
            $this->addError('notNumber');
            return false;
        }

        return true;
    }
}
