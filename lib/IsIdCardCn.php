<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Chinese identity card
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsIdCardCn extends BaseValidator
{
    private const CHECKSUM_X = 10;

    /**
     * @var int
     * @internal Use to generate messages
     */
    protected $length = 18;

    protected $lengthMessage = '%name% must have a length of %length%';

    protected $invalidMessage = '%name% is incorrect';

    protected $negativeMessage = '%name% must not be an identity card number';

    /**
     * Calculate the final digit of id card
     *
     * @param string $input The 17 or 18-digit code
     * @return string
     */
    public function calcChecksum($input)
    {
        $weighting = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $sum = 0;

        for ($i = 16; $i >= 0; --$i) {
            $sum += $input[$i] * $weighting[$i];
        }

        $checksum = (12 - $sum % 11) % 11;

        return self::CHECKSUM_X == $checksum ? 'X' : (string) $checksum;
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if ($this->length !== strlen($input)) {
            $this->addError('length');
            return false;
        }

        // The 1 to 17-digit must be digit
        if (!preg_match('/^([0-9]+)$/', substr($input, 0, -1))) {
            $this->addError('invalid');
            return false;
        }

        // Verify date of birth
        $month = substr($input, 10, 2);
        $day = substr($input, 12, 2);
        $year = substr($input, 6, 4);
        if (!checkdate($month, $day, $year)) {
            $this->addError('invalid');
            return false;
        }

        // Verify checksum
        $checksum = $this->calcChecksum($input);
        if (strtoupper($input[17]) !== $checksum) {
            $this->addError('invalid');
            return false;
        }

        return true;
    }
}
