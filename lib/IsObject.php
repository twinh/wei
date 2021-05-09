<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is an object
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsObject extends BaseValidator
{
    /**
     * {@inheritdoc}
     */
    public const BASIC_TYPE = true;

    protected $notObjectMessage = '%name% must be an object';

    protected $negativeMessage = '%name% must not be an object';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_object($input)) {
            $this->addError('notObject');
            return false;
        }
        return true;
    }
}
