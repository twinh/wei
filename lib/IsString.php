<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input could be convert to string
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsString extends BaseValidator
{
    /**
     * {@inheritdoc}
     */
    public const BASIC_TYPE = true;

    /**
     * @var int|null
     */
    protected $minLength;

    /**
     * @var int|null
     */
    protected $maxLength;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, int $minLength = null, int $maxLength = null)
    {
        null !== $minLength && $this->storeOption('minLength', $minLength);
        null !== $maxLength && $this->storeOption('maxLength', $maxLength);

        return $this->isValid($input);
    }

    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (null !== $this->minLength) {
            $result = $this->validateRule($input, 'minLength', $this->minLength);
            if (!$result) {
                return $result;
            }
        }

        if (null !== $this->maxLength) {
            return $this->validateRule($input, 'maxLength', $this->maxLength);
        }

        return true;
    }
}
