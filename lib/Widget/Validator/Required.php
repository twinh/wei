<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is provided
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Required extends AbstractValidator
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
    protected function validate($input)
    {
        if ($this->required && in_array($input, $this->invalid, true)) {
            $this->addError('required');
            return false;
        }
        return true;
    }
}
