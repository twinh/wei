<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the length (or size) of input is lower than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MaxLength extends Length
{
    protected $tooLongMessage = '%name% must have a length lower than %max%';

    protected $tooManyMessage = '%name% must contain no more than %max% items';

    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $max = null, $__ = null)
    {
        $max && $this->storeOption('max', $max);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
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
