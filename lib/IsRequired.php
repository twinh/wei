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
     * The invalid variable values
     *
     * @var array
     */
    protected $invalid = [null, '', false, []];

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $required = null)
    {
        is_bool($required) && $this->storeOption('required', $required);

        return $this->isValid($input);
    }

    /**
     * Check if the input is in invalid variables
     *
     * @param mixed $input
     * @return bool
     */
    public function isInvalid($input)
    {
        // When validating array or object data, if the data contains key, the value is considered to exist
        if ($this->validator && $this->validator->hasField($this->validator->getCurrentField())) {
            return false;
        }

        return in_array($input, $this->invalid, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if ($this->required && $this->isInvalid($input)) {
            $this->addError('required');
            return false;
        }
        return true;
    }
}
