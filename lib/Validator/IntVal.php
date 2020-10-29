<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input could be convert to int
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IntVal extends BaseValidator
{
    protected $notIntMessage = '%name% must be an integer value';

    protected $negativeMessage = '%name% must not be an integer value';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isInt($input)) {
            $this->addError('notInt');
            return false;
        }
        return true;
    }

    private function isInt($input)
    {
        if (is_int($input)) {
            return true;
        }

        if ($this->isString($input) && $input[0] === '-') {
            return ctype_digit(substr($input, 1));
        }
        return ctype_digit($input);
    }
}
