<?php
/**
 * Zhcn
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
 * @package     Common
 * @subpackage  Language
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-12-07 17:09:37
 */

class Common_Config_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_GROUP_ID' => '分组编号',
            'LBL_FIELD_FORM_TYPE' => '表单类型',
            'LBL_FIELD_FORM_WIDGET' => '表单微件',
            'LBL_FIELD_LABEL' => '名称',
            'LBL_FIELD_FORM_NAME' => '表单名称',
            'LBL_FIELD_FORM_LABEL' => '表单标题',
            'LBL_FIELD_FORM_RESOURCE' => '表单资源',

            'LBL_GROUP_FORM_SETTING' => '表单配置',

            'LBL_CONFIG_CENTER' => '配置中心',

            'LBL_CONTROLLER_CONFIG' => '配置',
            'LBL_MODULE_CONFIG' => '配置',
            'LBL_MODULE_CONFIG_GROUP' => '配置分组',
        );
    }
}
