<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is between the specified minimum and maximum value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsBetween extends BaseValidator
{
    protected $betweenMessage = '%name% must between %min% and %max%';

    protected $negativeMessage = '%name% must not between %min% and %max%';

    protected $min;

    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $min = null, $max = null)
    {
        // Allows non numeric parameter like 2000-01-01, 10:03, etc
        if (null !== $min && null !== $max) {
            $this->storeOption('min', $min);
            $this->storeOption('max', $max);
        }

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if ($this->min > $input || $this->max < $input) {
            $this->addError('between');
            return false;
        }
        return true;
    }
}
