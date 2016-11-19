<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is NOT valid by all of specified rules
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class NoneOf extends SomeOf
{
    protected $invalidMessage = '%name% must be passed by all of these rules';

    protected $combineMessages = false;

    /**
     * {@inheritdoc}
     * @param void $__ Avoid compatible error
     */
    public function __invoke($input, array $rules = array(), $__ = null)
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
        $props = array(
            'name' => $this->name,
            'negative' => true
        );
        foreach ($this->rules as $rule => $options) {
            if (!$this->validate->validateOne($rule, $input, $options, $validator, $props)) {
                $this->validators[$rule] = $validator;
            }
        }

        if (!$this->validators) {
            $this->errors = array();
            return true;
        } else {
            return false;
        }
    }
}
