<?php
/**
 * Service
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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
 * @package     Qwin
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 15:30:37
 */

class Trex_Service
{
    /**
     * 未知错误
     */
    const ERROR_UNKNOWN     = 1;

    /**
     * 用户配置错误
     */
    const ERROR_CONFIG      = 2;

    /**
     * 验证错误
     */
    const ERROR_VALIDATE    = 3;

    /**
     * 将第二个数组合并到第一个上
     *
     * @param array $array1
     * @param array $array2
     * @return array 合并的数组
     */
    protected function _multiArrayMerge(array $array1 = null, array $array2 = null)
    {
        if(null == $array2)
        {
            return $array1;
        }
        foreach($array2 as $key => $val)
        {
            if(is_array($val))
            {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->_multiArrayMerge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }

    /**
     * 执行触发器
     *
     * @param string $name 触发器名称
     * @param array $config 服务的配置
     * @return mixed
     */
    public function executeTrigger($name, $config)
    {
        if(null != $config['trigger'][$name])
        {
            return Qwin::callByArray($config['trigger'][$name]);
        }
        return null;
    }
}
