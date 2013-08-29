<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is empty
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class EmptyValue extends BaseValidator
{
    protected $emptyMessage = '%name% must be empty';

    protected $negativeMessage = '%name% must not be empty';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!empty($input)) {
            $this->addError('empty');
            return false;
        }

        return true;
    }
}
