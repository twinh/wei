<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input could be convert to array
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsArray extends BaseValidator
{
    public const VALID_TYPE = 'array';

    protected $notArrayMessage = '%name% must be an array';

    protected $negativeMessage = '%name% must not be an array';

    /**
     * @var int|null
     */
    protected $minLength;

    /**
     * @var int|null
     */
    protected $maxLength;

    public function __invoke($input, ?int $minLength = null, ?int $maxLength = null)
    {
        null !== $minLength && $this->storeOption('minLength', $minLength);
        null !== $maxLength && $this->storeOption('maxLength', $maxLength);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_array($input) && !$input instanceof \ArrayAccess && !$input instanceof \SimpleXMLElement) {
            $this->addError('notArray');
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
