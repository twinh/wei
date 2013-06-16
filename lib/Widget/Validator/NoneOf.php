<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is NOT valid by all off specified rules
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    \Widget\Is $is The validator manager
 */
class NoneOf extends AbstractGroupValidator
{
    protected $invalidMessage = '%name% must be passed by all of these rules';

    protected $rules = array();

    protected $validators = array();

    protected $combineMessages = false;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, array $rules = array())
    {
        $rules && $this->storeOption('rules', $rules);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        $this->addError('invalid');

        $validator = null;
        $props = array(
            'name' => $this->name,
            'negative' => true
        );
        foreach ($this->rules as $rule => $options) {
            if (!$this->is->validateOne($rule, $input, $options, $validator, $props)) {
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
