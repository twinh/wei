<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is less than or equal to (<=) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsLessThanOrEqual extends IsEqualTo
{
    protected $invalidMessage = '%name% must be less than or equal to %value%';

    protected $negativeMessage = '%name% must not be less than or equal to %value%';

    /**
     * {@inheritdoc}
     */
    protected function doCompare($input)
    {
        return $input <= $this->value;
    }
}
