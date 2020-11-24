<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is not empty
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsPresent extends BaseValidator
{
    protected $emptyMessage = '%name% must not be empty';

    protected $negativeMessage = '%name% must be empty';

    /**
     * The invalid variables
     *
     * @var array
     */
    protected $invalid = [null, '', false, []];

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
