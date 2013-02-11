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
    public function combine(array $messages)
    {
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
