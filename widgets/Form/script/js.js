/**
 * js
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
 * @since       v0.7.0 2011-02-16 17:54:11
 */

/**
 * 验证代码键名为表单的id,值为jQuery Validate的验证规则和错误信息
 *
 * @var array
 */
var validateCode = new Array();
jQuery(function($){
    $('form.qw-form input, form.qw-form textarea, form.qw-form select').addClass('ui-widget-content ui-corner-all');
    
    // TODO 如何不影响输入框的数据,如使用锁的图标表示只读,或提示语浮动置于表单最右端
    $('form.qw-form input[readonly], form.qw-form textarea[readonly]').each(function(){
        $('label[for="' + $(this).attr('id') + '"]').prepend('<span title="Readonly" class="qw-form-readonly ui-icon ui-icon-locked">&nbsp;&nbsp;&nbsp;</span>');
        $(this).addClass('ui-priority-secondary');
    });
    $('div.qw-icon-common a').qui();

    $('fieldset > legend').click(function(){
        $(this).next().toggle();
    });

    if (undefined != $.validator) {
        for (var form in validateCode) {
            // 为必选项增加星号标识
            for (var rule in validateCode[form]['rules']) {
                if (undefined != validateCode[form]['rules'][rule]['required']) {
                    $('label[for="' + rule + '"]').prepend('<span class="ui-validator-required>*</span>');
                }
            }
            // 定义表单验证
            $('#' + form).validate({
                rules: validateCode[form]['rules'],
                messages: validateCode[form]['messages'],
                //errorClass: 'ui-state-error',
                errorPlacement: function(error, element) {
                    error.appendTo( element.parent());
                },
                success: function(label) {
                    label.addClass('ui-icon ui-icon-check').html('check!');
                },
                submitHandler: function(form){
                    form.submit();
                },
                highlight: function(input){
                    $(input).addClass('ui-state-highlight');
                },
                unhighlight: function(input){
                    $(input).removeClass('ui-state-highlight');
                }
            });
        }
    }

    // todo 动态切换浏览模式
});