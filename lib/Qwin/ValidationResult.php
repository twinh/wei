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

namespace Qwin;

/**
 * ValidationResult
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-23 22:06:18
 */
class ValidationResult extends Widget
{
    /**
     * Validated result
     *
     * @var bool
     */
    public $source = true;

    /**
     * Invalidated rules
     *
     * @var array
     */
    protected $_invalidatedRules = array();

    /**
     * Validated rules
     *
     * @var array
     */
    protected $_validatedRules = array();

    /**
     * Get new Qwin_ValidationResult instance
     *
     * @return Qwin_ValidationResult
     */
    public function __invoke()
    {
        return new self();
    }

    /**
     * Get Invalidated rules
     *
     * @return array
     */
    public function getInvalidatedRules()
    {
        return array_keys($this->_invalidatedRules);
    }

    /**
     * Get validated rules
     *
     * @return array
     */
    public function getValidatedRules()
    {
        return array_keys($this->_validatedRules);
    }

    /**
     * Add invalidated rule
     *
     * @param  string                $rule
     * @return Qwin_ValidationResult
     */
    public function addInvalidatedRule($rule)
    {
        $this->_invalidatedRules[$rule] = true;

        return $this;
    }

    /**
     * Add validated rule
     *
     * @param  string                $rule
     * @return Qwin_ValidationResult
     */
    public function addValidatedRule($rule)
    {
        $this->_validatedRules[$rule] = true;

        return $this;
    }

    /**
     * Remove invalidated rule
     *
     * @param  string                $rule
     * @return Qwin_ValidationResult
     */
    public function removeInvalidatedRule($rule)
    {
        if (isset($this->_invalidatedRules[$rule])) {
            unset($this->_invalidatedRules[$rule]);
        }

        return $this;
    }

    /**
     * Removed validated rule
     *
     * @param  string                $rule
     * @return Qwin_ValidationResult
     */
    public function removeValidatedRule($rule)
    {
        if (isset($this->_validatedRules[$rule])) {
            unset($this->_validatedRules[$rule]);
        }

        return $this;
    }

    /**
     * Check if $rule invalidated
     *
     * @param  string $rule
     * @return bool
     */
    public function isInvalidated($rule)
    {
        return isset($this->_invalidatedRules[$rule]);
    }

    /**
     * Check if $rule validated
     *
     * @param  string $rule
     * @return bool
     */
    public function isValidated($rule)
    {
        return isset($this->_validatedRules[$rule]);
    }
}
