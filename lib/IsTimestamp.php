<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a valid database timestamp
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsTimestamp extends IsAnyDateTime
{
    protected $format = 'Y-m-d H:i:s';

    protected $min = '1970-01-01 00:00:01';

    protected $max = '2038-01-19 03:14:07';

    protected function doValidate($input)
    {
        $result = parent::doValidate($input);
        if (!$result) {
            return $result;
        }

        return $this->validateRule($input, 'between', [
            'min' => $this->min,
            'max' => $this->max,
        ]);
    }
}
