<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a true value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsTrue extends BaseValidator
{
    public const VALID_TYPE = 'bool';

    protected $invalidMessage = '%name% is not valid';

    protected $negativeMessage = '%name% is not valid';

    /**
     * Allow values from user input
     *
     * @var array
     */
    protected $values = [
        true,
        1,
        '1',
    ];

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $invalidMessage = null)
    {
        null !== $invalidMessage && $this->storeOption('invalidMessage', $invalidMessage);
        return parent::__invoke($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!in_array($input, $this->values, true)) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
}
