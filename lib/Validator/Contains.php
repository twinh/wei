<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is contains the specified string or pattern
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Contains extends BaseValidator
{
    protected $notContainsMessage = '%name% must contains %search%';

    protected $negativeMessage = '%name% must not contains %search%';

    protected $search;

    protected $regex = false;

    /**
     * Returns whether the $input value is valid
     *
     * @param mixed $input
     * @param string $search
     * @param bool $regex
     * @return bool
     */
    public function __invoke($input, $search = null, $regex = false)
    {
        $search && $this->storeOption('search', (string)$search);
        $this->storeOption('regex', $regex);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (!$this->regex) {
            if (strpos($input, $this->search) === false) {
                $this->addError('notContains');
                return false;
            }
        } else {
            if (!preg_match($this->search, $input)) {
                $this->addError('notContains');
                return false;
            }
        }

        return true;
    }
}
