<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input could be divisible by specified divisor
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsDivisibleBy extends BaseValidator
{
    protected $notDivisibleMessage = '%name% must be divisible by %divisor%';

    protected $negativeMessage = '%name% must not be divisible by %divisor%';

    /**
     * Set divisor to 1 to avoid "division by zero" error
     *
     * @var int|float
     */
    protected $divisor = 1;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $divisor = null)
    {
        $divisor && $this->storeOption('divisor', $divisor);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notDivisible');
            return false;
        }

        if (is_float($this->divisor)) {
            $result = fmod($input, $this->divisor);
        } else {
            $result = (int) $input % $this->divisor;
        }

        if (0 != $result) {
            $this->addError('notDivisible');
            return false;
        }
        return true;
    }
}
