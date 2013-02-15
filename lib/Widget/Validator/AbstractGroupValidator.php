<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

class AbstractGroupValidator extends AbstractValidator
{
    /**
     * The invalid validators
     * 
     * @var array<\Widget\Validator\AbstractValidator>
     */
    protected $validators = array();
    
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
        $messages = parent::getMessages();
        $key = key($messages);
        
        foreach ($this->validators as $rule => $validator) {
            $messages[$rule] = implode(';', $validator->getMessages());
        }

        return array(
            $key => implode("\n", $messages)
        );
    }
}
