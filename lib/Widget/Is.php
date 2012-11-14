<?php
/**
 * Widget Framework
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

namespace Widget;

/**
 * Is
 *
 * @package     Widget
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-14 15:32:49
 * @todo        'funcMap'       => array(
            'isArray'       => 'is_array',
            'isBool'        => 'is_bool',
            'isInt'         => 'is_int',
            'isNull'        => 'is_null',
            'isNumeric'     => 'is_numeric',
            'isScalar'      => 'is_scalar',
            'isString'      => 'is_string',
        ),
 */
class Is extends WidgetProvider
{
    public $options = array(
        'rules' => array(),
        'data' => array(),
        'break' => false,
        'breakOne' => false,
        'validatedOne' => null,
        'invalidatedOne' => null,
        'validated' => null,
        'invalidated' => null,
        'success' => null,
        'failure' => null,
    );

    protected $_validationResult;

    public function __invoke(array $options = array())
    {
        $options = $options + $this->options;
        $this->options = &$options;

        if (empty($options['rules'])) {
            throw new Exception('Rules should not be empty.');
        }

        // set true for default and will change to false when invalidated
        $result = true;

        $validationResult = new Widget_ValidationResult;
        $this->_validationResult = $validationResult;

        foreach ($options['rules'] as $name => $rules) {
            $data = isset($options['data'][$name]) ? $options['data'][$name] : null;

            // make required rule at first
            if (!isset($rules['required'])) {
                $value = true;
            } else {
                $value = (bool) $rules['required'];
                unset($rules['required']);
            }
            $rules = array('required' => $value) + $rules;

            foreach ($rules as $rule => $params) {
                $widget = 'is' . ucfirst($rule);

                // prepare parameters for validte widget
                $params = (array) $params;
                array_unshift($params, $data);

                // TODO check if rule is exists
                if (false === $this->callback(array($this, $widget), $params)) {
                    // would be always false in the whole valid flow
                    $result = false;

                    $validationResult->addInvalidatedRule($rule);

                    // trigger invalidatedOne event
                    $options['invalidatedOne'] && $this->callback($options['invalidatedOne'], array(
                        $name, $rule, $params, $data, $rules, $validationResult, $this,
                    ));

                    if ($options['breakOne']) {
                        break;
                    }
                } else {
                    // trigger validatedOne event
                    $options['validatedOne'] && $this->callback($options['validatedOne'], array(
                        $name, $rule, $params, $data, $rules, $validationResult, $this,
                    ));

                    // goto next rules
                    if (!$data && 'required' == $rule) {
                        break;
                    }
                }
            }

            if ($result) {
                $options['validated'] && $this->callback($options['validated'], array(
                    $name, $rules, $validationResult, $this,
                ));
            } else {
                $options['invalidated'] && $this->callback($options['invalidated'], array(
                    $name, $rules, $validationResult, $this,
                ));

                if ($options['breakOne'] || $options['break']) {
                    break;
                }
            }
        }

        $event = $result ? 'success' : 'failure';
        $options[$event] && $this->callback($options[$event], array(
            $validationResult, $this,
        ));

        return $result;
    }

    /**
     * Get last validation result
     *
     * @return Widget_ValidationResult
     */
    public function getLastValidationResult()
    {
        return $this->_validationResult;
    }

    public function isRequired($data, $required)
    {
        return !$required || $data;
    }
}
