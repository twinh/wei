<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Hong Kong identity card
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsIdCardHk extends BaseValidator
{
    protected $invalidMessage = '%name% must be valid Hong Kong identity card';

    protected $negativeMessage = '%name% must not be valid Hong Kong identity card';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (8 != strlen($input)) {
            $this->addError('invalid');
            return false;
        }

        $input = strtoupper($input);

        // The first char should be A-Z
        $first = ord($input[0]);
        if ($first < 65 || $first > 90) {
            $this->addError('invalid');
            return false;
        }

        // c1 = ord(c1) - 64 => A=1, B=2, ... , Z=26
        // sum = c1*8 + c2*7 + ... + c6*3 + c5*2
        $sum = ($first - 64) * 8;
        for ($i = 1, $j = 7; $i < 7; $i++, $j--) {
            $sum += $input[$i] * $j;
        }

        $checksum = $sum % 11;
        if (1 == $checksum) {
            $checksum = 'A';
        } elseif ($checksum > 1) {
            $checksum = 11 - $checksum;
        }

        if ($checksum != $input[7]) {
            $this->addError('invalid');
            return false;
        }

        return true;
    }
}
