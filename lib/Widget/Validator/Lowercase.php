<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is lowsercase
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Lowercase extends BaseValidator
{
    protected $invalidMessage = '%name% must be lowercase';

    protected $negativeMessage = '%name% must not be lowercase';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (mb_strtolower($input, mb_detect_encoding($input)) != $input) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
}
