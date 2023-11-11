<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input could be convert to int
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsInt extends BaseValidator
{
    public const VALID_TYPE = 'int';

    protected $notIntMessage = '%name% must be an integer value';

    protected $negativeMessage = '%name% must not be an integer value';

    /**
     * @var int|string|null
     */
    protected $min;

    /**
     * @var int|string|null
     */
    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, int $min = null, int $max = null)
    {
        null !== $min && $this->storeOption('min', $min);
        null !== $max && $this->storeOption('max', $max);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isInt($input)) {
            $this->addError('notInt');
            return false;
        }

        if (null !== $this->min) {
            $result = $this->validateRule($input, 'gte', $this->min);
            if (!$result) {
                return $result;
            }
        }

        if (null !== $this->max) {
            $result = $this->validateRule($input, 'lte', $this->max);
            if (!$result) {
                return $result;
            }
        }

        return true;
    }

    private function isInt($input)
    {
        if (is_int($input)) {
            return true;
        }

        // True will be convert to "1"
        if (true === $input) {
            return false;
        }

        $input = $this->toString($input);
        if (null === $input) {
            return false;
        }

        if (isset($input[0]) && '-' === $input[0]) {
            return ctype_digit(substr($input, 1));
        }
        return ctype_digit($input);
    }
}
