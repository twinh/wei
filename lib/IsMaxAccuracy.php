<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the number of digits after the decimal point of the input is lower than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsMaxAccuracy extends BaseValidator
{
    protected $notNumberMessage = '%name% must be valid number';

    protected $maxMessage = '%name% can have at most %max% decimal(s)';

    /**
     * The max number of digits after the decimal point
     *
     * @var int
     */
    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $max = null)
    {
        $max && $this->storeOption('max', $max);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_numeric($input)) {
            $this->addError('notNumber');
            return false;
        }

        if ($this->getDecimalCount($input) > $this->max) {
            $this->addError('max');
            return false;
        }

        return true;
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
