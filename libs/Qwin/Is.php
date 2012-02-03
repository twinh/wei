<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Is
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-14 15:32:49
 */
class Qwin_Is extends Qwin_Widget
{
    public $options = array(
        'rules' => array(),
        'break' => false,
        'validated' => null,
        'invalid' => null,
        'success' => null,
        'failure' => null,
    );

    public $_validationResult;

    public function call($rule, array $options = array())
    {
        // prepare options
        if ($rule instanceof Qwin_Widget) {
            $rule = $rule->source;
        }

        if (is_string($rule)) {
            $rules = explode(', ', $rule);

            foreach ($rules as $rule) {
                $rule = trim($rule);
                if (empty($rule)) {
                    return $this->exception('Rule should not be empty.');
                }

                if (false === strpos($rule, '=')) {
                    $options['rules'][$rule] = true;
                } else {
                    $rule = explode('=', $rule);
                    $options['rules'][$rule[0]] = explode(',', $rule[1]);
                }
            }

            if ($options) {
                $options = $options + $this->options;
            }
        } elseif (is_array($rule)) {
            $options = $rule + $this->options;
        }
        $this->options = &$options;

        $rules = $options['rules'];
        $value = &$this->source;

        // set true for default and will change when invalid
        $result = true;

        $validationResult = new Qwin_ValidationResult;
        $this->_validationResult = $validationResult;

        if (!isset($rules['required']) && !$value) {
            if ($options['success']) {
                $this->callback($options['success'], array(
                    $value, $validationResult, $this,
                ));
            }
            return true;
        } else {
            // pass required validation
            $validationResult->addValidatedRule('required');

            // trigger validated event
            if ($options['validated']) {
                $this->callback($options['validated'], array(
                    'required', true, $value, $validationResult, $this,
                ));
            }

            unset($rules['required']);
        }

        // valid the other rules
        foreach ($rules as $rule => $params) {
            $widget = 'is' . ucfirst($rule);

            // TODO check if rule is exists
            if (false === $this->callback(array($this, $widget), (array)$params)) {
                // would be always false in the whole valid flow
                $result = false;

                $validationResult->addInvalidRule($rule);

                // trigger invalid event
                if ($options['invalid']) {
                    $this->callback($options['invalid'], array(
                        $rule, $params, $value, $validationResult, $this,
                    ));
                }

                if ($options['break']) {
                    if ($options['failure']) {
                        $this->callback($options['failure'], array(
                            $value, $validationResult, $this,
                        ));
                    }
                    return false;
                }
            } else {
                // trigger validated event
                if ($options['validated']) {
                    $this->callback($options['validated'], array(
                        $rule, $params, $value, $validationResult, $this,
                    ));
                }
            }
        }

        $event = $result ? 'success' : 'failure';
        if ($options[$event]) {
            $this->callback($options[$event], array(
                $value, $validationResult, $this,
            ));
        }

        return $result;
    }

    /**
     * Get last validation result
     *
     * @return Qwin_ValidationResult
     */
    public function getLastValidationResult()
    {
        return $this->_validationResult;
    }
}