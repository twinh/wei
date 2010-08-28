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
 * @package     Trex
 * @subpackage  Common
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 19:09:43
 */

class Trex_Common_Language_Zhcn extends Trex_Language
{
    public function __construct()
    {
        $this->_data += array(
            '' => 'NULL',

            // Qwin's information
            'LBL_QWIN' => 'Qwin',
            'LBL_QWIN_VERSION' => '3.0Beta',

            // action
            'LBL_ACTION_LIST' => '列表',
            'LBL_ACTION_ADD' => '添加',
            'LBL_ACTION_EDIT' => '编辑',
            'LBL_ACTION_DELETE' => '删除',
            'LBL_ACTION_View' => '查看',
            'LBL_ACTION_COPY' => '复制',
            'LBL_ACTION_FILTER' => '筛选',
            'LBL_ACTION_RETURN' => '返回',
            'LBL_ACTION_RESET' => '重置',
            'LBL_ACTION_SUBMIT' => '提交',
            'LBL_ACTION_APPLY' => '应用',
            'LBL_ACTION_RESTORE' => 'Restore',
            'LBL_ACTION_REDIRECT' => '跳转',

            'LBL_DEFAULT' => '默认',
            'LBL_OPERATION' => '操作',
            'LBL_SWITCH_DISPLAY_MODEL' => '切换显示模式',
            'LBL_SHORTCUT' => '快捷方式',

            // 页眉
            'LBL_STYLE' => '风格',
            'LBL_THEME' => '主题',
            'LBL_WELCOME' => '欢迎您',
            'LBL_LOGOUT' => '注销',
            'LBL_TOOL' => '工具',
            'LBL_LANG' => '语言',

            // field
            'LBL_FIELD_ID' => '编号',
            'LBL_FIELD_NAME' => '名称',
            'LBL_FIELD_NAMESPACE' => '命名空间',
            'LBL_FIELD_TITLE' => '标题',
            'LBL_FIELD_OPERATION' => '操作',
            'LBL_FIELD_DATE_CREATED' => '创建时间',
            'LBL_FIELD_DATE_MODIFIED' => '修改时间',
            'LBL_FIELD_CAPTCHA' => '验证码',
            'LBL_FIELD_DESCRIPTION' => '描述',
            'LBL_FIELD_CONTENT' => '内容',

            'MSG_CHOOSE_ONLY_ONE_ROW' => '请只选择一行!',
            'MSG_CHOOSE_AT_LEASE_ONE_ROW' => '请选择至少一行!',
            'MSG_CONFIRM_TO_DELETE' => '删除后将无法还原,确认?',
            'MSG_ERROR_FIELD' => '错误域: ',
            'MSG_ERROR_MSG' => '错误信息: ',
            'MSG_NO_RECORD' => '该记录不存在或已经被删除.',
            'MSG_ERROR_CAPTCHA' => '验证码错误',

            'LBL_GROUP_BASIC_DATA' => '基本资料',

            'LBL_HTML_TITLE' => 'Content Management System - Powered by QWin Framework',
            'LBL_FOOTER_COPYRIGHT' => 'Powered by <a>Qwin Framework</a>. Copyright &copy; 2009-2010 <a>Twin</a>. All rights reserved.',
        );
    }
}
