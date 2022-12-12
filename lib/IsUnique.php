<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use const SORT_REGULAR;

/**
 * Check if the input is not contains the same value
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsUnique extends BaseValidator
{
    /**
     * Change default flags from SORT_STRING to SORT_REGULAR to avoid "Array to string conversion"
     *
     * @var int
     */
    protected $flags = SORT_REGULAR;

    /**
     * @var string
     */
    protected $notArrayMessage = '%name% must be an array';

    /**
     * @var string
     */
    protected $notUniqueMessage = '%name% must not contain the same value';

    /**
     * @var string
     */
    protected $negativeMessage = '%name% must contain the same value';

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $flags = null)
    {
        null !== $flags && $this->storeOption('flags', $flags);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_array($input)) {
            $this->addError('notArray');
            return false;
        }

        if (array_unique($input, $this->flags) !== $input) {
            $this->addError('notUnique');
            return false;
        }

        return true;
    }
}
