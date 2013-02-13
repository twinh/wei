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
     * {@inheritdoc}
     */
    public function getMessages()
    {
        $messages = parent::getMessages();
        
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
         */
        $results = array();
        $key = key($messages);
        $results[] = array_shift($messages);
        
        foreach ($messages as $rule => $message) {
            $results[strstr($rule, '.', true)][] = $message;
        }
        
        foreach ($results as &$result) {
            if (is_array($result)) {
                $result = implode(';', $result);
            }
        }

        return array(
            $key => implode("\n", $results)
        );
    }
}
