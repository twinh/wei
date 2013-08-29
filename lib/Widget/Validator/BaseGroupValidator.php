<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

abstract class BaseGroupValidator extends BaseValidator
{
    /**
     * The invalid validators
     *
     * @var array<BaseValidator>
     */
    protected $validators = array();

    /**
     * Whether combine messages into single one or not
     *
     * @var bool
     */
    protected $combineMessages = true;

    /**
     * {@inheritdoc}
     */
    protected function reset()
    {
        $this->validators = array();

        parent::reset();
    }


    /**
     * {@inheritdoc}
     */
    public function getMessages()
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
                $messages[$rule] = implode(';', $validator->getMessages());
            }

            return array(
                $key => implode("\n", $messages)
            );
        } else {
            $messages = array();
            foreach ($this->validators as $rule => $validator) {
                foreach ($validator->getMessages() as $option => $message) {
                    $messages[$rule . '.' . $option] = $message;
                }
            }
            return $messages;
        }
    }
}
