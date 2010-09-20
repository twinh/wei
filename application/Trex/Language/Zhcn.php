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
 * @subpackage  Language
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 15:56:30
 */

class Trex_Language_Zhcn extends Trex_Language
{
    public function __construct()
    {
        $this->_data += array(
            '' => 'NULL',
            'LBL_QWIN' => 'Qwin',
            'LBL_QWIN_VERSION' => '3.0Beta',
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
            'LBL_ACTION_RESTORE' => '还原',
            'LBL_ACTION_REDIRECT' => '跳转',
            'LBL_DEFAULT' => '默认',
            'LBL_OPERATION' => '操作',
            'LBL_SWITCH_DISPLAY_MODEL' => '切换显示模式',
            'LBL_SHORTCUT' => '快捷方式',
            'LBL_STYLE' => '风格',
            'LBL_THEME' => '主题',
            'LBL_WELCOME' => '欢迎您',
            'LBL_LOGOUT' => '注销',
            'LBL_TOOL' => '工具',
            'LBL_LANG' => '语言',
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
            'LBL_HTML_TITLE' => 'Management System - Powered by QWin Framework',
            'LBL_FOOTER_COPYRIGHT' => 'Powered by Qwin Framework. Copyright © 2008-2010 Twin. All rights reserved.',
            'LBL_ACTION_VIEW' => '查看',
            'LBL_ACTION_UPDATE' => '更新',
            'LBL_LAST_VIEWED' => '最近查看',
            'LBL_MESSAGE' => '提示信息',
            'LBL_MEMBER_CENTER' => '用户中心',
            'LBL_MANAGEMENT' => '管理',
            'LBL_FIELD_CATEGORY_ID' => '分类编号',
            'LBL_FIELD_CATEGORY_NAME' => '分类名称',
            'LBL_FIELD_CREATOR' => '创建者',
            'LBL_FIELD_MODIFIER' => '修改者',
            'LBL_FIELD_CREATED_BY' => '创建者',
            'LBL_FIELD_MODIFIED_BY' => '修改者',
            'LBL_FIELD_SUMMARY' => '概要',
            'LBL_FIELD_TYPE' => '类型',
            'LBL_FIELD_PARENT_NAME' => '父名称',
            'LBL_FIELD_PARENT_ID' => '父编号',
            'LBL_FIELD_VALUE' => '值',
            'LBL_GROUP_DETAIL_DATA' => '详细资料',
            'LBL_GROUP_DEFAULT_DATA' => '默认资料',
            'MSG_OPERATE_SUCCESSFULLY' => '操作成功!',
            'MSG_FUNCTION_DEVELOPTING' => '功能尚在开发中.',
            'MSG_CLICK_TO_REDIRECT' => '3秒后跳转到新的页面,点击"跳转"按钮直接跳转.',
            'MSG_NOT_ALLOW_DELETE' => '不允许删除该用户.',
            'MSG_FILE_NOT_EXISTS' => '文件不存在',
            'MSG_PERMISSION_NOT_ENOUGH' => '您不够权限查看或操作该页面.',
            'LBL_FIELD_CONTACT_ID' => '联系人编号',
            'LBL_FIELD_FIRST_NAME' => '名称',
            'LBL_FIELD_LAST_NAME' => '姓',
            'LBL_FIELD_NICKNAME' => '昵称',
            'LBL_FIELD_PHOTO' => '照片',
            'LBL_FIELD_RELATION' => '关系',
            'LBL_FIELD_BIRTHDAY' => '生日',
            'LBL_FIELD_EMAIL' => '邮件',
            'LBL_FIELD_TELEPHONE' => '电话',
            'LBL_FIELD_MOBILE' => '手机',
            'LBL_FIELD_SEX' => '性别',
            'LBL_FIELD_HOMEPAGE' => '主页',
            'LBL_FIELD_ADDRESS' => '地址',
            'LBL_FIELD_RELATED_MODULE' => '相关模块',
            'LBL_FIELD_ORDER' => '顺序',
            
            'MSG_VALIDATOR_REQUIRED' => '该项是必填的.',
            'MSG_VALIDATOR_REMOTE' => '请修正该项的值.',
            'MSG_VALIDATOR_EMAIL' => '请输入合法的邮箱.',
            'MSG_VALIDATOR_URL' => '请输入合法的地址.',
            'MSG_VALIDATOR_DATE' => '请输入合法的日期.',
            'MSG_VALIDATOR_DATEISO' => '请输入合法的日期(ISO).',
            'MSG_VALIDATOR_NUMBER' => '请输入合法的数字.',
            'MSG_VALIDATOR_DIGITS' => '请只输入数字.',
            'MSG_VALIDATOR_CREDITCARD' => '请输入有效的信用卡号.',
            'MSG_VALIDATOR_EQUALTO' => '请再次输入相同的值.',
            'MSG_VALIDATOR_ACCEPT' => '请输入合法的后缀名.',
            'MSG_VALIDATOR_MAXLENGTH' => '请输入长度最多是 {0} 的字符串.',
            'MSG_VALIDATOR_MINLENGTH' => '请输入长度最少是 {0} 的字符串.',
            'MSG_VALIDATOR_RANGELENGTH' => '请输入长度在 {0} 和 {1} 之间的字符串.',
            'MSG_VALIDATOR_RANGE' => '请输入在 {0} 和 {1} 之间的值.',
            'MSG_VALIDATOR_MAX' => '请输入小于或等于 {0} 的值.',
            'MSG_VALIDATOR_MIN' => '请输入大于或等于 {0} 的值.',
            'MSG_VALIDATOR_PATHNAME' => '请输入合法的路径名称,不包含 \\/:*?\\"<>| .',
            'MSG_VALIDATOR_NOTNULL' => '该项是必填的.',
            'LBL_FORM_VALUE_ADVICE' => '表单赋值建议',
            'LBL_FIELD' => '域',
            'LBL_VALUE' => '值',
            'LBL_TYPE' => '类型',
            'LBL_TYPE_NOT_IN' => '不包含',
        );
    }
}
