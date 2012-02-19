<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * Callback
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-13 15:23:16
 */
class Qwin_Callback extends Qwin_Widget
{
    /**
     * 处理回调结构
     *
     * @param mixed $callback 回调结构
     * @param array $params 数组参数
     * @return mixed
     */
    public function __invoke($name, array $params = null)
    {
        if ($this->isCallable($name)) {
            $callback = null;
            if (is_string($name)) {
                $callback = $this->isCallable->getStringFn($name);
            }
            if (!$callback) {
                $callback = $name;
            }
            return call_user_func_array($callback, (array)$params);
        }
        return $this->exception('Parameter 1 should be a valid callback');
    }
}
