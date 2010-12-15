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
 * @since       2010-10-22 10:46:03
 */

class Common_CommonClass_Language_Gbk extends Common_Language_Gbk
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_CODE' => '代码',
            'LBL_FIELD_VALUE' => '值',
            'LBL_FIELD_VAR_NAME' => '变量名称',
            'LBL_FIELD_TYPE' => '类型',

            'LBL_ACTION_ADD_NEXT' => '添加下一个分类',

            'LBL_MODULE_COMMONCLASS' => '通用分类',
            'LBL_FIELD_LANGUAGE' => '语言',
            'LBL_FIELD_SIGN' => '标识',

        );
    }
}
