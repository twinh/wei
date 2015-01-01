<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is identical to (===) specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IdenticalTo extends EqualTo
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
