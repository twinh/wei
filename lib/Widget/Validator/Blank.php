<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is blank
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Blank extends BaseValidator
{
    protected $blankMessage = '%name% must be blank';

    protected $negativeMessage = '%name% must not be blank';

    protected $invalid = array(null, '', false, array());

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (
            in_array($input, $this->invalid, true)
            || ($this->isString($input) && '' === trim($input))
        ) {
            return true;
        } else {
            $this->addError('blank');
            return false;
        }
    }
}
