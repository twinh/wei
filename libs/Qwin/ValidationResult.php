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
 * ValidResult
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-23 22:06:18
 */
class Qwin_ValidationResult extends Qwin_Widget
{
    public $source = true;

    protected $_invalidRules = array();

    protected $_validateRules = array();

    public function call()
    {
        return $this;
    }

    public function getInvalidRules()
    {
        return array_keys($this->_invalidRules);
    }

    public function getValidatedRules()
    {
        return array_keys($this->_validateRules);
    }

    public function addInvalidRule($rule)
    {
        $this->_invalidRules[$rule] = true;
        return $this;
    }

    public function addValidatedRule($rule)
    {
        $this->_validateRules[$rule] = true;
        return $this;
    }

    public function removeInvalidRule($rule)
    {
        if (isset($this->_invalidRules[$rule])) {
            unset($this->_invalidRules[$rule]);
        }
        return $this;
    }

    public function removeValidateRule()
    {
        if (isset($this->_validateRules[$rule])) {
            unset($this->_validateRules[$rule]);
        }
        return $this;
    }

    public function isInvalid($rule)
    {
        return isset($this->_invalidRules[$rule]);
    }

    public function isValidated($rule)
    {
        return isset($this->_validateRules[$rule]);
    }
}