<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is null
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Null extends BaseValidator
{
    protected $notNullMessage = '%name% must be null';

    protected $negativeMessage = '%name% must not be null';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_null($input)) {
            $this->addError('notNull');
            return false;
        }

        return true;
    }
}
