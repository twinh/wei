<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is less than (<) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class LessThan extends EqualTo
{
    protected $invalidMessage = '%name% must be less than %value%';

    protected $negativeMessage = '%name% must not be less than %value%';

    /**
     * {@inheritdoc}
     */
    protected function doCompare($input)
    {
        return $input < $this->value;
    }
}