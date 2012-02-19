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
 * IsCallable
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-23 13:10:30
 */
class Qwin_IsCallable extends Qwin_Widget
{
    protected $_index = 1;

    protected $_stringFns = array();

    public $options = array(
        'stringFn' => true,
    );

    public function __invoke($name, $syntaxOnly = false, &$callableName = null)
    {
        if (is_callable($name, $syntaxOnly, &$callableName)) {
            return true;
        }

        if (!$this->options['stringFn'] || !is_string($name)) {
            return false;
        }

        if (isset($this->_stringFns[$name])) {
            return true;
        }

        $callback = ltrim($name);
        if ('function' == substr($callback, 0, 8)) {
            $fn = 'qwin_lambda_' . ($this->_index++);

            // convert lambda string function to runtime function
            $callback = 'function ' . $fn . ' ' . substr($callback, 8);

            // show error to user ?
            eval($callback);

            // valid whether function has been created
            if (function_exists($fn)) {
                $this->_stringFns[$name] = $fn;
                return true;
            }
        }

        return false;
    }

    public function getStringFn($name)
    {
        return isset($this->_stringFns[$name]) ? $this->_stringFns[$name] : null;
    }
}
