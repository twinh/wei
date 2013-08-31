<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the length (or size) of input is greater than specified length
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MinLength extends Length
{
    protected $tooShortMessage = '%name% must have a length greater than %min%';

    protected $tooFewMessage = '%name% must contain at least %min% item(s)';

    protected $min;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $min = null, $__ = null)
    {
        $min && $this->storeOption('min', $min);

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

        if ($this->min > $len) {
            $this->addError(is_scalar($input) ? 'tooShort' : 'tooFew');
            return false;
        }

        return true;
    }
}
