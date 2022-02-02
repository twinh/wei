<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is greater than (>=) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsGreaterThan extends IsEqualTo
{
    protected $invalidMessage = '%name% must be greater than %value%';

    protected $negativeMessage = '%name% must not be greater than %value%';

    /**
     * {@inheritdoc}
     */
    protected function doCompare($input)
    {
        return $input > $this->value;
    }
}
