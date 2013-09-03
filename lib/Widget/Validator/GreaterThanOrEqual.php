<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is greater than or equal to (>=) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class GreaterThanOrEqual extends EqualTo
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
