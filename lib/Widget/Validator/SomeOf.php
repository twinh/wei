<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid by specified number of the rules
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    \Widget\Is $is The validator manager
 */
class SomeOf extends AbstractGroupValidator
{
    protected $atLeastMessage = '%name% must be passed by at least %left% of %count% rules';

    protected $rules = array();

    /**
     * How many rules should pass at least
     *
     * @var int
     */
    protected $atLeast;

    /**
     * The passed rules number, using for message only
     *
     * @var string
     */
    protected $count;

    /**
     * The not passed rules number, using for message only
     *
     * @var string
     */
    protected $left;

    public function __invoke($input, array $rules = array(), $atLeast = null)
    {
        $atLeast && $this->storeOption('atLeast', $atLeast);
        $rules && $this->storeOption('rules', $rules);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        // Adds "atLeast" error at first, make sure this error at the top of
        // stack, if any rule is passed, the error will be removed
        $this->addError('atLeast');

        $passed = 0;
        $validator = null;
        $props = array('name' => $this->name);
        foreach ($this->rules as $rule => $options) {
            if ($this->is->validateOne($rule, $input, $options, $validator, $props)) {
                $passed++;
                if ($passed >= $this->atLeast) {
                    // Removes all error messages
                    $this->errors = array();
                    return true;
                }
            } else {
                $this->validators[$rule] = $validator;
            }
        }

        $this->count = count($this->rules) - $passed;
        $this->left = $this->atLeast - $passed;

        return false;
    }
}
