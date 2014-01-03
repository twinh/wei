<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

use DateTime as Dt;

/**
 * Check if the input is a valid datetime
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class DateTime extends BaseValidator
{
    /**
     * Message occurred when thrown "Failed to parse time string" exception
     *
     * @var string
     */
    protected $invalidMessage = '%name% must be a valid datetime';

    /**
     * Message occurred when datetime format not match "format" property
     *
     * @var string
     */
    protected $formatMessage = '%name% must be a valid datetime, the format should be "%format%", e.g. %example%';

    /**
     * The error message for "before" property
     *
     * @var string
     */
    protected $tooLateMessage = '%name% must be earlier than %before%';

    /**
     * The error message for "after" property
     *
     * @var string
     */
    protected $tooEarlyMessage = '%name% must be later than %after%';

    protected $negativeMessage = '%name% must not be a valid datetime';

    /**
     * The Datetime format string
     *
     * @link http://www.php.net/manual/en/datetime.createfromformat.php
     * @var string
     */
    protected $format = null;

    /**
     * @var string
     */
    protected $before;

    /**
     * @var string
     */
    protected $after;

    /**
     * The example datetime parameter for error message
     *
     * @var string
     */
    protected $example;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $format = null)
    {
        // Options
        if (is_array($format)) {
            $this->storeOption($format);
        } elseif ($format) {
            $this->storeOption('format', $format);
        }

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        try {
            if ($this->format) {
                $date = Dt::createFromFormat($this->format, $input);
            } else {
                $date = new Dt($input);
            }
        } catch (\Exception $e) {
            $date = false;
        }

        // Case 1: cannot parse time by specified format($this->format)
        // Case 2: thrown exception "Failed to parse time string..."
        if (false === $date) {
            $this->addError('invalid');
            return false;
        }

        if ($this->format && $input != $date->format($this->format)) {
            $this->example = date($this->format);
            $this->addError('format');
            return false;
        }

        if ($this->before) {
            $before = new Dt($this->before);
            if ($before < $date) {
                $this->addError('tooLate');
            }
        }

        if ($this->after) {
            $after = new Dt($this->after);
            if ($after > $date) {
                $this->addError('tooEarly');
            }
        }

        return !$this->errors;
    }
}
