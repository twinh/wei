<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use ReflectionClass;

/**
 * Check if the input is one of the class const
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsInConst extends BaseValidator
{
    /**
     * The name of class or the object
     *
     * @var string|object|null
     */
    protected $class;

    /**
     * The prefix of class constant
     *
     * @var string|null
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $notInMessage = '%name% must be in the specified data';

    /**
     * @var string
     */
    protected $negativeMessage = '%name% must not be in the specified data';

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $class = '', $prefix = null)
    {
        $class && $this->storeOption('class', $class);
        $prefix && $this->storeOption('prefix', $prefix);
        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        $reflect = new ReflectionClass($this->class);
        $consts = $reflect->getConstants();

        $length = null === $this->prefix ? 0 : strlen($this->prefix);
        foreach ($consts as $key => $value) {
            if ($value != $input) {
                continue;
            }

            if (!$this->prefix || substr($key, 0, $length) === $this->prefix) {
                return true;
            }
        }

        $this->addError('notIn');
        return false;
    }
}
