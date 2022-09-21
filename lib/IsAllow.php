<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Ignore the remaining rules of current field if input value is in the specified values
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @internal    This is not a really validator, may be change in the future
 */
final class IsAllow extends BaseValidator
{
    /**
     * The allowed values
     *
     * @var array
     */
    protected $values = [];

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, ...$values)
    {
        $values && $this->values = $values;
        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (in_array($input, $this->values, true)) {
            $this->validator->skipNextRules();
        }
        return true;
    }
}
