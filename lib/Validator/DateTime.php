<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

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

        if ($this->format) {
            $date = date_create_from_format($this->format, $input);
        } else {
            $date = date_create($input);
        }

        $lastErrors = date_get_last_errors();
        if ($lastErrors['warning_count'] || $lastErrors['error_count']) {
            $this->addError('invalid');
            return false;
        }

        if ($this->before) {
            $before = date_create($this->before);
            if ($before < $date) {
                $this->addError('tooLate');
            }
        }

        if ($this->after) {
            $after = date_create($this->after);
            if ($after > $date) {
                $this->addError('tooEarly');
            }
        }

        return !$this->errors;
    }
}
