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
class IsEmpty extends BaseValidator
{
    protected $emptyMessage = '%name% must be empty';

    protected $negativeMessage = '%name% must not be empty';

    /**
     * The invalid variables
     *
     * Note that 0, 0.0 and "0" are considered to be not empty
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
            return true;
        }

        $this->addError('empty');
        return false;
    }
}
