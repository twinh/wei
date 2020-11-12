<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a bool value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsBool extends BaseValidator
{
    /**
     * {@inheritDoc}
     */
    public const BASIC_TYPE = true;

    protected $notBoolMessage = '%name% must be a bool value';

    protected $negativeMessage = '%name% must not be a bool value';

    /**
     * Notes that "on", "off", "yes" and "no" are not allowed，since that `(bool) $var` will convert to `true`.
     * To allow these values, use `Boolable` instead.
     *
     * @var array
     */
    protected $values = [
        true,
        false,
        1,
        0,
        '1',
        '0',
        '',
        null,
    ];

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!in_array($input, $this->values, true)) {
            $this->addError('notBool');
            return false;
        }
        return true;
    }
}
