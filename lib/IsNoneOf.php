<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is NOT valid by all of specified rules
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsNoneOf extends IsSomeOf
{
    protected $invalidMessage = '%name% must be passed by all of these rules';

    protected $combineMessages = false;

    /**
     * {@inheritdoc}
     * @param void $ignore Avoid compatible error
     */
    public function __invoke($input, array $rules = [], $ignore = null)
    {
        $rules && $this->storeOption('rules', $rules);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        $this->addError('invalid');

        $validator = null;
        $props = [
            'name' => $this->name,
            'negative' => true,
        ];
        foreach ($this->rules as $rule => $options) {
            if (!$this->validate->validateOne($rule, $input, $options, $validator, $props)) {
                $this->validators[$rule] = $validator;
            }
        }

        if (!$this->validators) {
            $this->errors = [];
            return true;
        } else {
            return false;
        }
    }
}
