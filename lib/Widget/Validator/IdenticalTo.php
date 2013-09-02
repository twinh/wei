<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is identical to (===) specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IdenticalTo extends EqualTo
{
    protected $invalidMessage = '%name% must be identical to %value%';

    protected $negativeMessage = '%name% must not be identical to %equals%';

    /**
     * {@inheritdoc}
     */
    protected function doCompare($input)
    {
        return $input === $this->value;
    }
}
