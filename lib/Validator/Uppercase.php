<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is uppercase
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Uppercase extends BaseValidator
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

        if (mb_strtoupper($input, mb_detect_encoding($input)) != $input) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
}
