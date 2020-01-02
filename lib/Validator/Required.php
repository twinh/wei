<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is provided
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Required extends BaseValidator
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
    protected $invalid = array(null, '', false, array());

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $required = null)
    {
        is_bool($required) && $this->storeOption('required', $required);

        return $this->isValid($input);
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

    /**
     * Check if the input is in invalid variables
     *
     * @param mixed $input
     * @return bool
     */
    public function isInvalid($input)
    {
        return in_array($input, $this->invalid, true);
    }
}
