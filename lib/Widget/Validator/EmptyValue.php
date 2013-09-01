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
     * The invalid variable values
     *
     * @var array
     */
    protected $invalid = array(null, '', false, array());

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (in_array($input, $this->invalid, true)) {
            return true;
        } else {
            $this->addError('empty');
            return false;
        }
    }
}
