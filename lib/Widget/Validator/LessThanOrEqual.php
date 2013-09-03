<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is less than or equal to (<=) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class LessThanOrEqual extends EqualTo
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