/**
 * optioneditor
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-20 21:12:58
 */
jQuery(function($){
    jQuery.fn.outerHTML = function(s) {
        return (s)
            ? this.before(s).remove()
            : jQuery('<p>').append(this.eq(0).clone()).html();
    }
    
    // 绑定删除按钮
    $('#ui-optioneditor-sortable .ui-optioneditor-oper a')
        .qui()
        .find('span.ui-icon-circle-close')
        .live('click', function(){
            deleteItem($(this));
        });

    // 点击添加按钮
    $('.ui-optioneditor-button').click(function(){
        var number = parseInt($('#ui-optioneditor-number').val());
        if (0 >= number ) {
            number = 1;
        }
        for (var i = 0; i < number; i++) {
            addItem();
        }
    });

    // 删除一项
    function deleteItem(object)
    {
        var liList = $('.ui-optioneditor li');
        // 如果是最后一个,则清空数据,不删除
        if (1 == liList.length) {
            clearItemForm(liList[0]);
        } else {
            object.parents('li').slideUp('normal', function(){
                $(this).remove();
            })
        }
    }

    // 清空表单数据
    function clearItemForm(element)
    {
        element = $(element);
        element.find('input').val('');
        element.find('option:first').attr('selected','selected');
        return element;
    }

    // 添加一项
    function addItem()
    {
        // 替换所有表单的名称
        var html = $('.ui-optioneditor li:first').clone().outerHTML();
        var rand = Math.floor(Math.random() * 10000);
        html = html.replace(/name=\"(\w+)+\[(.+?)\]\[(.+?)\]\"/g, 'name="$1[' + rand + '][$3]"');
        var item = clearItemForm($(html))
            .hide()
            .appendTo($('#ui-optioneditor-sortable'))
            .slideDown()
        item
            .find('input')
            .qui()
            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e){
                e.stopImmediatePropagation();
            });
        item
            .find('a')
            .qui();
    }

    // 排列
    $('#ui-optioneditor-sortable').sortable({
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,
        opacity: 0.9,
        scroll: false,
        create: function () {
            $(this).find('input')
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                    e.stopImmediatePropagation();
            });
        },
        stop: function () {
            $(this).find('input')
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                    e.stopImmediatePropagation();
            });
        }
    }).disableSelection();

    // 重置顺序
    /*$('.ui-optioneditor-reset').click(function(){
        $('#ui-optioneditor-sortable').sortable();
    });*/

    // 防止表单提交,转变为添加选项
    $('#ui-optioneditor-number').keypress(function(e){
        if(e.which == 13){
            $('.ui-optioneditor-button').click();
            return false;
        }
        return true;
    });

    // 至少显示3个选项,如果超过3个,则再显示一个做新增用
    var i = 3 - $('.ui-optioneditor li').length;
    do {
        addItem();
        i--;
    } while(i > 0);
});