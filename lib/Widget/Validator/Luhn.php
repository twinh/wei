<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid by the Luhn algorithm
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Luhn extends BaseValidator
{
    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        $checksum = $this->getChecksum(substr($input, 0, -1));
        if ($checksum != substr($input, -1)) {
            return false;
        }

        return true;
    }

    /**
     * Return the checksum char of luhn algorithm
     *
     * @param $string
     * @return string
     */
    protected function getChecksum($string)
    {
        $len    = strlen($string);
        $sum    = 0;
        $offset = $len % 2;

        for ($i = 0; $i < $len; $i++) {
            if (0 == ($i + $offset) % 2) {
                $add = $string[$i] * 2;
                $sum += $add > 9 ? $add - 9 : $add;
            } else {
                $sum += $string[$i];
            }
        }

        return 10 - $sum % 10;
    }
}