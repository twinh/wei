<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input could be convert to array
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class ArrayVal extends BaseValidator
{
    protected $notArrayMessage = '%name% must be an array';

    protected $negativeMessage = '%name% must not be an array';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_array($input) && !$input instanceof \ArrayAccess && !$input instanceof \SimpleXMLElement) {
            $this->addError('notArray');
            return false;
        }
        return true;
    }
}
