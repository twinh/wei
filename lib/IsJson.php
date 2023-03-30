<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a database JSON array or object
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsJson extends BaseValidator
{
    protected $notJsonMessage = '%name% must be an array or object';

    protected $negativeMessage = '%name% must not be an array or object';

    protected $tooLongMessage = '%name% must be no more than %max% character(s)';

    /**
     * @var int|string
     * @experimental better message, length detect
     */
    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $max = null)
    {
        null !== $max && $this->storeOption('max', $max);
        return parent::__invoke($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_array($input) && !is_object($input)) {
            $this->addError('notJson');
            return false;
        }

        if (
            $this->max
            && mb_strlen(json_encode($input, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE)) > $this->max
        ) {
            $this->addError('tooLong');
            return false;
        }

        return true;
    }
}
