<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
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
    /**
     * {@inheritDoc}
     */
    public const BASIC_TYPE = true;

    protected $notIntMessage = '%name% must be an integer value';

    protected $negativeMessage = '%name% must not be an integer value';

    /**
     * @var int|null
     */
    protected $min;

    /**
     * @var int|null
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

        if ($this->isString($input) && $input[0] === '-') {
            return ctype_digit(substr($input, 1));
        }
        return ctype_digit($input);
    }
}
