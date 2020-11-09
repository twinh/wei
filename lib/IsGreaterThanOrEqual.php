<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is greater than or equal to (>=) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsGreaterThanOrEqual extends IsEqualTo
{
    protected $invalidMessage = '%name% must be greater than or equal to %value%';

    protected $negativeMessage = '%name% must not be greater than or equal to %value%';

    /**
     * {@inheritdoc}
     */
    protected function doCompare($input)
    {
        return $input >= $this->value;
    }
}
