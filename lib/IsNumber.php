<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is number
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsNumber extends BaseValidator
{
    protected $notNumberMessage = '%name% must be valid number';

    protected $scaleMessage = '%name% can have at most %scale% decimal(s)';

    protected $lessThanMessage = '%name% must be greater than or equal to %value%';

    protected $greaterThanMessage = '%name% must be less than or equal to %value%';

    protected $precisionMessage = '%name% can have';

    protected $negativeMessage = '%name% must not be number';

    /**
     * The number of digits in the input
     *
     * @var int|null
     */
    protected $precision;

    /**
     * The number of digits after the decimal point
     *
     * @var int|null
     */
    protected $scale;

    /**
     * Whether the input must be greater than or equal to 0
     *
     * To enable this option, use `uNumber` rule instead.
     *
     * @var bool
     */
    protected $unsigned = false;

    /**
     * The calculated number for $this->lessThanMessage and $this->greaterThanMessage
     *
     * @var string
     * @internal
     */
    protected $value;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, int $precision = null, int $scale = null)
    {
        if (null !== $precision && $scale > $precision) {
            throw new \InvalidArgumentException('Precision must be greater than or equals scale');
        }

        null !== $precision && $this->storeOption('precision', $precision);
        null !== $scale && $this->storeOption('scale', $scale);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_numeric($input) || is_nan($input)) {
            $this->addError('notNumber');
            return false;
        }

        if (null !== $this->scale && $this->scale < $this->getDecimalCount($input)) {
            $this->addError('scale');
            return false;
        }

        if (null === $this->precision) {
            return true;
        }

        [$min, $max] = $this->getRange();
        if ($input > $max) {
            $this->value = $max;
            $this->addError('greaterThan');
            return false;
        }

        if ($input < $min) {
            $this->value = $min;
            $this->addError('lessThan');
            return false;
        }

        return true;
    }

    /**
     * @return int[]
     */
    private function getRange()
    {
        $max = (str_repeat(9, $this->precision - $this->scale) ?: '0')
            . ($this->scale ? ('.' . str_repeat(9, $this->scale)) : '');

        $min = $this->unsigned ? 0 : -$max;

        return [$min, $max];
    }

    /**
     * @param mixed $number
     * @return int
     * @link https://stackoverflow.com/a/12525070
     */
    private function getDecimalCount($number)
    {
        $precision = 0;
        while (true) {
            if ((string) $number === (string) round($number)) {
                break;
            }
            if (is_infinite($number)) {
                break;
            }
            $number *= 10;
            $precision++;
        }
        return $precision;
    }
}
