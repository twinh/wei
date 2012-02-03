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
 * ValidationResult
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-23 22:06:18
 */
class Qwin_ValidationResult extends Qwin_Widget
{
    /**
     * Validate result
     *
     * @var bool
     */
    public $source = true;

    /**
     * Invalid rules
     *
     * @var array
     */
    protected $_invalidRules = array();

    /**
     * Validate rules
     *
     * @var array
     */
    protected $_validateRules = array();

    /**
     * Get new Qwin_ValidationResult instance
     *
     * @return Qwin_ValidationResult
     */
    public function call($result = true)
    {
        return new self($result);
    }

    /**
     * Get invalid rules
     *
     * @return array
     */
    public function getInvalidRules()
    {
        return array_keys($this->_invalidRules);
    }

    /**
     * Get validated rules
     *
     * @return array
     */
    public function getValidatedRules()
    {
        return array_keys($this->_validateRules);
    }

    /**
     * Add invalid rule
     *
     * @param string $rule
     * @return Qwin_ValidationResult
     */
    public function addInvalidRule($rule)
    {
        $this->_invalidRules[$rule] = true;
        return $this;
    }

    /**
     * Add validated rule
     *
     * @param string $rule
     * @return Qwin_ValidationResult
     */
    public function addValidatedRule($rule)
    {
        $this->_validateRules[$rule] = true;
        return $this;
    }

    /**
     * Remove invalid rule
     *
     * @param string $rule
     * @return Qwin_ValidationResult
     */
    public function removeInvalidRule($rule)
    {
        if (isset($this->_invalidRules[$rule])) {
            unset($this->_invalidRules[$rule]);
        }
        return $this;
    }

    /**
     * Removed validated rule
     *
     * @param string $rule
     * @return Qwin_ValidationResult
     */
    public function removeValidateRule($rule)
    {
        if (isset($this->_validateRules[$rule])) {
            unset($this->_validateRules[$rule]);
        }
        return $this;
    }

    /**
     * Check if $rule invalid
     *
     * @param string $rule
     * @return bool
     */
    public function isInvalid($rule)
    {
        return isset($this->_invalidRules[$rule]);
    }

    /**
     * Check if $rule validated
     *
     * @param string $rule
     * @return bool
     */
    public function isValidated($rule)
    {
        return isset($this->_validateRules[$rule]);
    }
}