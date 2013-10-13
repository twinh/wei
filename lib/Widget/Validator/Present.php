<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is not empty
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Present extends BaseValidator
{
    protected $emptyMessage = '%name% must not be empty';

    protected $negativeMessage = '%name% must be empty';

    /**
     * The invalid variables
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
            $this->addError('empty');
            return false;
        } else {
            return true;
        }
    }
}
