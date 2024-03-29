<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the length (or size) of input is lower than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsMaxLength extends IsLength
{
    public const VALID_TYPE = 'string';

    protected $tooLongMessage = '%name% must have a length lower than %max%';

    protected $tooManyMessage = '%name% must contain no more than %max% items';

    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $max = null, $ignore = null)
    {
        $max && $this->storeOption('max', $max);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if ($this->countByChars && !$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (false === ($len = $this->getLength($input))) {
            $this->addError('notDetected');
            return false;
        }

        if ($this->max < $len) {
            $this->addError(is_scalar($input) ? 'tooLong' : 'tooMany');
            return false;
        }

        return true;
    }
}
