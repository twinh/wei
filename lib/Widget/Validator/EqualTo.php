<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is equals to specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class EqualTo extends BaseValidator
{
    protected $notEqualsMessage = '%name% must be equals %equals%';

    protected $negativeMessage = '%name% must not be equals %equals%';

    /**
     * The value to be compared
     *
     * @var mixed
     */
    protected $value;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $value = null)
    {
        // Sets $this->equals only when the second argument provided
        func_num_args() > 1 && $this->storeOption('value', $value);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if ($input != $this->value) {
            $this->addError('notEquals');
            return false;
        }
        return true;
    }
}
