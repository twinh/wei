<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese identity card
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IdCardCn extends AbstractValidator
{
    protected $invalidMessage = '%name% must be valid Chinese identity card';

    protected $negativeMessage = '%name% must not be valid Chinese identity card';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        $len = strlen($input);
        if ($len != 15 && $len != 18) {
            $this->addError('invalid');
            return false;
        }

        // Upgrade to 18-digit
        if ($len == 15) {
            $input = substr($input, 0, 6) . '19' . substr($input, 6);
        }

        // The 1 to 17-digit must be digit
        if (!preg_match('/^([0-9]+)$/', substr($input, 0, -1))) {
            $this->addError('invalid');
            return false;
        }

        // Verify date of birth
        $month  = substr($input, 10, 2);
        $day    = substr($input, 12, 2);
        $year   = substr($input, 6, 4);
        if (!checkdate($month, $day, $year)) {
            $this->addError('invalid');
            return false;
        }

        // Verify checksum
        $checksum = $this->calcChecksum($input);
        if (isset($input[17])) {
            if ($input[17] !== $checksum) {
                $this->addError('invalid');
                return false;
            }
        }

        return true;
    }

    /**
      * Calculate the final digit of id card
      *
      * @param string $input The 17 or 18-digit code
      * @return string
      */
    public function calcChecksum($input)
    {
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $sum = 0;

        for ($i = 16; $i >= 0; $i--) {
            $sum += $input[$i] * $wi[$i];
        }

        $checksum = (12 - $sum % 11) % 11;

        return $checksum == 10 ? 'X' : (string)$checksum;
    }
}
