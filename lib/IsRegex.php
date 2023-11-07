<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid by specified regular expression
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsRegex extends BaseValidator
{
    public const VALID_TYPE = 'string';

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
     * @param string|null $pattern
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
        $input = $this->toString($input);
        if (null === $input) {
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
