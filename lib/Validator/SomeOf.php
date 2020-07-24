<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid by specified number of the rules
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class SomeOf extends BaseValidator
{
    protected $atLeastMessage = '%name% must be passed by at least %left% of %count% rules';

    /**
     * The validator rules
     *
     * Format:
     * array(
     *     'email' => true,
     *     'endsWith' => array(
     *         'findMe' => '@google.com'
     *     )
     * );
     * @var array
     */
    protected $rules = [];

    /**
     * How many rules should pass at least
     *
     * @var int
     */
    protected $atLeast;

    /**
     * Whether combine messages into single one or not
     *
     * @var bool
     */
    protected $combineMessages = true;

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

    /**
     * The invalid validators
     *
     * @var BaseValidator[]
     */
    protected $validators = [];

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, array $rules = [], $atLeast = null)
    {
        $atLeast && $this->storeOption('atLeast', $atLeast);
        $rules && $this->storeOption('rules', $rules);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages($name = null)
    {
        /**
         * Combines messages into single one
         *
         * FROM
         * array(
         *   'atLeast'          => 'atLeast message',
         *   'validator.rule'   => 'first message',
         *   'validator.rul2'   => 'second message',
         *   'validator2.rule'  => 'third message'
         * )
         * TO
         * array(
         *   'atLeast' => "atLeast message\n"
         *              . "first message;second message\n"
         *              . "third message"
         * )
         */
        if ($this->combineMessages) {
            $messages = parent::getMessages();
            $key = key($messages);

            foreach ($this->validators as $rule => $validator) {
                $messages[$rule] = implode(';', $validator->getMessages($name));
            }

            return [
                $key => implode("\n", $messages),
            ];
        } else {
            $messages = [];
            foreach ($this->validators as $rule => $validator) {
                foreach ($validator->getMessages($name) as $option => $message) {
                    $messages[$rule . '.' . $option] = $message;
                }
            }
            return $messages;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        // Adds "atLeast" error at first, make sure this error at the top of
        // stack, if any rule is passed, the error will be removed
        $this->addError('atLeast');

        $passed = 0;
        $validator = null;
        $props = ['name' => $this->name];
        foreach ($this->rules as $rule => $options) {
            if ($this->validate->validateOne($rule, $input, $options, $validator, $props)) {
                ++$passed;
                if ($passed >= $this->atLeast) {
                    // Removes all error messages
                    $this->errors = [];
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

    /**
     * {@inheritdoc}
     */
    protected function reset()
    {
        $this->validators = [];
        parent::reset();
    }
}
