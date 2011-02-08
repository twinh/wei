<?php
/**
 * Gbk
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
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-22 10:42:54
 */

class Common_Management_Language_Gbk extends Common_Language_Gbk
{
    public function  __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_MODULE_APPLICATION_STRUCTURE' => '应用目录结构',


            'LBL_FIELD_MODULE' => '模块',

            'LBL_ACTION_UPDATE_APPLICATION_STRUCTURE' => '更新应用目录结构文件',
            'LBL_ACTION_ADD_NAMESPACE' => '添加命名空间',
            'LBL_ACTION_NAMESPACE_LIST' => '命名空间列表',
            'LBL_ACTION_ADD_MODULE' => '添加模块',
            'LBL_ACTION_VIEW_MODULE' => '查看模块',

            'MSG_VALIDATOR_NAMESPACE_EXISTS' => '命名空间已存在.',
            'MSG_NAMESAPCE_NOT_EXISTS' => '命名空间不存在.',
            'MSG_NAMESPACE_NOT_EMPTY' => '命名空间文件夹不为空,不能被删除.',
            'MSG_MODULE_EMPTY' => '该命名空间不包含任何模块.',
            'MSG_MODULE_EXISTS' => '模块已存在.',

            'LBL_MANAGEMENT' => '管理',
            'LBL_NAMESPACE' => '命名空间',
            'LBL_MODULE' => '模块',
        );
    }
}
