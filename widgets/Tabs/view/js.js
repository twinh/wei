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
 * @since       2011-04-17 09:28:38 v0.7.9
 */
jQuery(function($){
    qwin.page.tabs = {
        tmpl: '<li id="qw-tabs-li-${id}" class="ui-widget ui-state-default ui-corner-top">'
            + '<span class="qw-tabs-close ui-icon ui-icon-close"></span>'
            + '<a href="${url}">${title}</a>'
            + '</li>',
        obj: $('#qw-tabs'),
        lastId: '1',
        init: function(){
            var id = qwin.page.tabs.encode(window.location.search);
            var tmpl = $.tmpl(qwin.page.tabs.tmpl, {
                    id: id,
                    url: window.location.search,
                    title: 'Default'
                }).qui();
            
            // 点击选项卡,显示对应内容
            tmpl.click(function(){
                qwin.page.tabs.show($(this).find('a'));
                return false;
            });
            $('#qw-content-1').attr('id', 'qw-content-' + id);
            qwin.page.tabs.obj.append(tmpl);
        },
        show: function(a){
            var id = qwin.page.tabs.encode($(a).attr('href'));

            // 增加选项卡
            if (!document.getElementById('qw-tabs-li-' + id)) {
                var tmpl = $.tmpl(qwin.page.tabs.tmpl, {
                    id: id,
                    url: $(a).attr('href'),
                    title: $(a).text()
                }).qui();
                // 点击选项卡,显示对应内容
                tmpl.click(function(){
                    qwin.page.tabs.show($(this).find('a'));
                    return false;
                });
                // 点击关闭按钮
                tmpl.find('span').click(function(){
                    qwin.page.tabs.remove(a);
                });
                qwin.page.tabs.obj.append(tmpl);
            }

            // 增加内容
            if (!document.getElementById('qw-content-' + id)) {
                $.ajax({
                    url: $(a).attr('href'),
                    data: 'view=content',
                    cache: false,
                    success: function(msg){
                        qwin.page.middle.append('<div id="qw-content-' + id + '">' + msg + '</div>');
                    }
                });
            }
            
            if (qwin.page.tabs.lastId == id) {
                return false;
            }
            
            // 隐藏上一个选项卡的内容
            $('#qw-tabs-li-' + qwin.page.tabs.lastId).removeClass('ui-state-active');
            $('#qw-content-' + qwin.page.tabs.lastId).hide();
           
            // 显示当前选项卡内容
            $('#qw-tabs-li-' + id).addClass('ui-state-active');
            $('#qw-content-' + id).show();
            
            qwin.page.tabs.lastId = id;
        },
        remove: function(a){
            var id = qwin.page.tabs.encode($(a).attr('href'));
            
            // 如果窗口是显示的,显示左边的窗口
            if ('none' != $('#qw-content-' + id).css('display')) {
                qwin.page.tabs.show($('#qw-tabs-li-' + id).prev().find('a'));
            }

            // 删除选项卡
            $('#qw-tabs-li-' + id).hide(500).remove();

            // 删除窗口
            $('#qw-content-' + id).remove();
        },
        // TODO 减少长度
        encode: function(id){
            var result = '';
            for (var i = 0, l = id.length; i < l; i++) {
                result += id.charCodeAt(i);
                if (200 < result.length) {
                    return result;
                }
            }
            return result;
        }
    };
    qwin.page.tabs.init();
    
    // 点击链接,根据情况决定是否生成选项卡
    $('a').click(function(){
        var url = $(this).attr('href');
        var parts = parseUri(url);
        
        if (parts.protocol) {
            return true;
        }
        
        if ('#' == url.substr(0, 1)) {
            return true;
        }
        
        qwin.page.tabs.show(this);
        return false;
    });
    qwin.page.tabs.obj.find('li').qui();
});
