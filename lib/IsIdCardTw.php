<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid Taiwan identity card
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://zh.wikipedia.org/wiki/%E4%B8%AD%E8%8F%AF%E6%B0%91%E5%9C%8B%E5%9C%8B%E6%B0%91%E8%BA%AB%E5%88%86%E8%AD%89
 */
class IsIdCardTw extends BaseValidator
{
    protected $invalidMessage = '%name% is incorrect';

    protected $negativeMessage = '%name% must not be an identity card number';

    protected $map = [
        'A' => 10,
        'B' => 11,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'I' => 34,
        'J' => 18,
        'K' => 19,
        'M' => 21,
        'N' => 22,
        'O' => 35,
        'P' => 23,
        'Q' => 24,
        'T' => 27,
        'U' => 28,
        'V' => 29,
        'W' => 32,
        'X' => 30,
        'Z' => 33,
        'L' => 20,
        'R' => 25,
        'S' => 26,
        'Y' => 31,
    ];

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (10 != strlen($input)) {
            $this->addError('invalid');
            return false;
        }

        $input = strtoupper($input);

        // Validate the city letter, should be A-Z
        $first = ord($input[0]);
        if ($first < 65 || $first > 90) {
            $this->addError('invalid');
            return false;
        }

        // Validate the gender
        if ('1' != $input[1] && '2' != $input[1]) {
            $this->addError('invalid');
            return false;
        }

        list($left, $right) = str_split((string) $this->map[$input[0]]);
        $sum = $left + 9 * $right;
        for ($i = 1, $j = 8; $i < 9; $i++, $j--) {
            $sum += $input[$i] * $j;
        }
        $sum += $input[9];

        if (0 !== $sum % 10) {
            $this->addError('invalid');
            return false;
        }

        return true;
    }
}
