<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input could be convert to string
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsStringVal extends BaseValidator
{
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        return true;
    }
}
