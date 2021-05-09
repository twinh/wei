<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is provided
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @@internal
 */
class IsRequired extends BaseValidator
{
    protected $requiredMessage = '%name% is required';

    /**
     * Whether the input is required or not
     *
     * @var bool
     */
    protected $required = true;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $required = null)
    {
        if (!$this->validator) {
            throw new \LogicException('The "required" validator should not call directly, please use with \Wei\V');
        }

        is_bool($required) && $this->storeOption('required', $required);

        return $this->isValid($input);
    }

    /**
     * Check if the input is in invalid variables
     *
     * @return bool
     */
    public function isInvalid()
    {
        // When validating array or object data, if the data contains key, the value is considered to exist
        return !$this->validator->hasField($this->validator->getCurrentField());
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if ($this->required && $this->isInvalid()) {
            $this->addError('required');
            return false;
        }
        return true;
    }
}
