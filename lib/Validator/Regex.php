<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid by specified regular expression
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Regex extends BaseValidator
{
    protected $patternMessage = '%name% must match against pattern "%pattern%"';

    protected $negativeMessage = '%name% must not match against pattern "%pattern%"';

    /**
     * The regex pattern
     *
     * @var string
     */
    protected $pattern;

    /**
     * Returns whether the $input value is valid
     *
     * @param mixed $input
     * @param null|string $pattern
     * @return bool
     */
    public function __invoke($input, $pattern = null)
    {
        is_string($pattern) && $this->storeOption('pattern', $pattern);

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

        if (!preg_match($this->pattern, $input)) {
            $this->addError('pattern');
            return false;
        }

        return true;
    }
}
