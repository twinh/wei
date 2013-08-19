<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the length (or size) of input is equals specified length or in
 * specified length range
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Length extends AbstractLengthValidator
{
    protected $lengthMessage = '%name% must have a length of %length%';

    protected $lengthItemMessage = '%name% must contain %length% item(s)';

    protected $notInMessage = '%name% must have a length between %min% and %max%';

    protected $notInItemMessage = '%name% must contain %min% to %max% item(s)';

    protected $min;

    protected $max;

    protected $length;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $min = null, $max = null)
    {
        // ($input, $min, $max)
        if (is_numeric($min) && is_numeric($max)) {
            $this->storeOption('min', $min);
            $this->storeOption('max', $max);
        // ($input, $length)
        } elseif (is_numeric($min) && is_null($max)) {
            $this->storeOption('length', $min);
        }

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

        if (!is_null($this->length)) {
            if ($this->length != $len) {
                $this->addError(is_scalar($input) ? 'length' : 'lengthItem');
                return false;
            }
        } elseif ($this->min > $len || $this->max < $len) {
            $this->addError(is_scalar($input) ? 'notIn' : 'notInItem');
            return false;
        }

        return true;
    }
}
