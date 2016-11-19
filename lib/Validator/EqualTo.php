<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is equals to (==) the specified value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class EqualTo extends BaseValidator
{
    protected $invalidMessage = '%name% must be equals %value%';

    protected $negativeMessage = '%name% must not be equals %value%';

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
        if (!$this->doCompare($input)) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }

    /**
     * Compare input and option value
     *
     * @param mixed $input
     * @return bool
     */
    protected function doCompare($input)
    {
        return $input == $this->value;
    }
}
