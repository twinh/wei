<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is identical to (===) specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsIdenticalTo extends IsEqualTo
{
    protected $invalidMessage = '%name% must be identical to %value%';

    protected $negativeMessage = '%name% must not be identical to %value%';

    /**
     * {@inheritdoc}
     */
    protected function doCompare($input)
    {
        return $input === $this->value;
    }
}
