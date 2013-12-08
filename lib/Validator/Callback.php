<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid by specified callback
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Callback extends BaseValidator
{
    protected $invalidMessage = '%name% is not valid';

    /**
     * The callback to validate the input
     *
     * @var callback
     */
    protected $fn;

    /**
     * Check if the input is valid by specified callback
     *
     * @param mixed $input The input value
     * @param \Closure|null $fn  The callback to validate the input
     * @param string|null $message The custom invalid message
     * @return bool
     */
    public function __invoke($input, \Closure $fn = null, $message = null)
    {
        $fn && $this->storeOption('fn', $fn);
        $message && $this->storeOption('message', $message);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!call_user_func($this->fn, $input, $this, $this->wei)) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }

    /**
     * Set the invalid message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
