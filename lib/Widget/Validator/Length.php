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
class Length extends BaseValidator
{
    protected $notDetectedMessage = '%name%\'s length could not be detected';

    protected $lengthMessage = '%name% must have a length of %length%';

    protected $lengthItemMessage = '%name% must contain %length% item(s)';

    protected $notInMessage = '%name% must have a length between %min% and %max%';

    protected $notInItemMessage = '%name% must contain %min% to %max% item(s)';

    protected $min;

    protected $max;

    /**
     * The required exactly length of input
     *
     * @var int
     */
    protected $length;

    /**
     * Whether count the string length by characters or bytes
     *
     * @var bool
     */
    protected $countByChars = false;

    /**
     * The character encoding
     *
     * @var string
     */
    protected $charset = 'UTF-8';

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

    /**
     * Return the input's length or false when could not detected
     *
     * @param string|array|\Countable $input
     * @return int|false
     */
    public function getLength($input)
    {
        if (is_scalar($input)) {
            if ($this->countByChars) {
                return mb_strlen($input, $this->charset);
            } else {
                return strlen($input);
            }
        } elseif (is_array($input) || $input instanceof \Countable) {
            return count($input);
        } else {
            return false;
        }
    }
}
