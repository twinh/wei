<?php
 /**
 * Validator
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
 * @subpackage  JQuery
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-02-01 13:30
 */

class Qwin_JQuery_Validator
{
    /**
     * 获取验证的 js 数据
     *
     * @param array $set 配置数组
     * @return json 验证规则的 json 数据
     */
    public function getRule($set)
    {
        $validator_rule = array();
        foreach($set as $field)
        {
            if(isset($field['validation']['rule']))
            {
                $validator_rule[$field['form']['name']] = $field['validation']['rule'];
            }
        }
        return Qwin::run('-arr')->jsonEncode($validator_rule);
    }
}
