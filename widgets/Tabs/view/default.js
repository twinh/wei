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
// TODO 减少冗余代码
// TODO ie,chrome下问题 
jQuery(function($){
    qwin.page.tabs = {
        tmpl: '<li id="qw-tabs-li-${id}" class="ui-widget ui-state-default ui-corner-top">'
            + '<span class="qw-tabs-close ui-icon ui-icon-close ui-priority-secondary"></span>'
            + '<a href="${url}">${title}</a>'
            + '</li>',
        iframeTmpl: '<iframe name="qw-tabs-iframe-${id}" id="qw-tabs-iframe-${id}" class="qw-tabs-iframe" frameborder="no" scrolling="auto" src="${url}"></iframe>',
        obj: $('#qw-tabs'),
        lastId: null,
        init: function(){
            // 点击链接,根据情况决定是否生成选项卡
            $('a').live('click', function(){
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
            
            var tabs = qwin.page.tabs.data;
            var lastId = qwin.page.tabs.lastId;
            // TODO 可以延迟加载选项卡内容,提高用户体验
            for (var i in tabs) {
                qwin.page.tabs.show('<a href="' + tabs[i]['url'] + '">' + tabs[i]['title'] + '</a>', false, false);
            }
            qwin.page.tabs.show('<a href="' + tabs[lastId]['url'] + '">' + tabs[lastId]['title'] + '</a>');
            
            // TODO 不允许移出窗口之外
            qwin.page.tabs.obj.sortable({
                axis: 'x'
            });
            
            $(document).click(qwin.page.tabs.clickToHideList);

            $('#qw-tabs-oper li').qui();
            $('#qw-tabs-prev').click(function(){
                qwin.page.tabs.showPrev();
            });
            $('#qw-tabs-next').click(function(){
                qwin.page.tabs.showNext();
            });
            // TODO 鼠标停留超过x ms,自动执行点击
            $('#qw-tabs-list span').click(function(event){
                qwin.page.tabs.toggleList(event);
            });
            $('#qw-tabs-grid').click(function(){
                qwin.page.tabs.showGrid();
            });
        },
        show: function(a, frame, post){
            var href = $(a).attr('href');
            var id = qwin.page.tabs.encode(href);

            // TODO 选项卡长度超出时如何处理
            // 增加选项卡
            if (!document.getElementById('qw-tabs-li-' + id)) {
                var tmpl = $.tmpl(qwin.page.tabs.tmpl, {
                    id: id,
                    url: href,
                    title: $(a).text()
                }).qui();
                
                // 通知服务器增加了该选项卡
                if('undefined' == typeof post || true == post) {
                    // TODO 增加加载中的提示
                    $.ajax({
                        url: '?module=member/tab&action=add',
                        type: 'POST',
                        data: {
                            url: href,
                            title: $(a).text()
                        }
                    });
                }
                
                // 点击选项卡,显示对应内容
                tmpl.click(function(){
                    qwin.page.tabs.show($(this).find('a'));
                    return false;
                });
                
                // 点击关闭按钮
                tmpl.find('span')
                    .hover(function(){
                        $(this).toggleClass('ui-icon-closethick ui-priority-secondary')
                    })
                    .click(function(){
                        qwin.page.tabs.remove(a);
                        // 阻止链接被激活
                        return false;
                    });
                    
                // 加入并显示
                tmpl.appendTo(qwin.page.tabs.obj)
                    .animate({
                        width: 'toggle'
                    }, 400);
            }

            // 增加内容
            if (('undefined' == typeof frame || true == frame) && !document.getElementById('qw-tabs-iframe-' + id)) {
                $.tmpl(qwin.page.tabs.iframeTmpl, {
                    id: id,
                    url: $(a).attr('href') + '&view-only=content'
                }).appendTo(qwin.page.middle);
                
                // 点击隐藏选项卡列表
                $(window.frames['qw-tabs-iframe-' + id]).click(qwin.page.tabs.clickToHideList);
            }
            
            if (qwin.page.tabs.lastId == id) {
                $('#qw-tabs-iframe-' + id).show();
                return false;
            }
            
            // 隐藏上一个选项卡的内容
            $('#qw-tabs-li-' + qwin.page.tabs.lastId).removeClass('ui-state-active');
            $('#qw-tabs-iframe-' + qwin.page.tabs.lastId).hide();

            // 显示当前选项卡内容
            $('#qw-tabs-li-' + id).addClass('ui-state-active');
            $('#qw-tabs-iframe-' + id).show();
            
            qwin.page.tabs.lastId = id;
            return false;
        },
        // TODO 如果没有前面,显示最后一个
        showPrev: function(){
            var prev = $('#qw-tabs-li-' + qwin.page.tabs.lastId).prev(),
                a;
            if (0 == prev.length) {
                a = qwin.page.tabs.obj.find('a:last');
            } else {
                a = prev.find('a');
            }
            return qwin.page.tabs.show(a);
        },
        showNext: function(){
            var next = $('#qw-tabs-li-' + qwin.page.tabs.lastId).next(),
                a;
            if (0 == next.length) {
                a = qwin.page.tabs.obj.find('a:first');
            } else {
                a = next.find('a');
            }
            return qwin.page.tabs.show(a);
        },
        remove: function(a){
            var href = $(a).attr('href');
            var id = qwin.page.tabs.encode(href);
            
            // 如果窗口是显示的,显示左边的窗口
            if ('none' != $('#qw-tabs-iframe-' + id).css('display')) {
                var newTab = $('#qw-tabs-li-' + id).prev();
                if (null == newTab.html()) {
                    newTab = $('#qw-tabs-li-' + id).next();
                }
                // 剩下最后一个,不可以删除
                if (null == newTab.html()) {
                    return false;
                }
                // TODO 先删除再显示选项卡
                qwin.page.tabs.show(newTab.find('a'));
            }
            
            // 通知服务器删除选项卡
            $.ajax({
                url: '?module=member/tab&action=remove',
                type: 'POST',
                data: {
                    url: href
                }
            });
            
            // 删除选项卡
            $('#qw-tabs-li-' + id).animate({
                width: 0,
                opacity: 0
            }, 400, function(){
                $(this).remove();
            });

            // 删除窗口
            $('#qw-tabs-iframe-' + id).remove();
            return true;
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
        },
        listObj: $('#qw-tabs-list ul'),
        listTmpl: '<li id="${id}"><a href="${url}">${title}</a></li>',
        listDisplay: 0,
        clickToHideList: function(event){
            if (1 == qwin.page.tabs.listDisplay && 0 == $(event.target).parents('#qw-tabs-list').length) {
                qwin.page.tabs.listDisplay = 0;
                qwin.page.tabs.listObj.slideUp();
            }
            return true;
        },
        toggleList: function(event){
            if (0 == qwin.page.tabs.listDisplay) {
                var lastId = 'qw-tabs-li-' + qwin.page.tabs.lastId,
                    link, lastItem;
                qwin.page.tabs.listObj.empty();
                $('#qw-tabs li').each(function(){
                    link = $(this).find('a');
                    $.tmpl(qwin.page.tabs.listTmpl, {
                        id: $(this).attr('id') + '-list',
                        url: link.attr('href'),
                        title: link.text()
                    }).appendTo(qwin.page.tabs.listObj);
                });
                lastItem = $('#qw-tabs-li-' + qwin.page.tabs.lastId + '-list').addClass('qw-tabs-list-current');
                qwin.page.tabs.listObj.find('li').hover(function(){
                    $(this).css('padding', '0').addClass('ui-state-hover');
                }, function(){
                    $(this).css('padding', '1px').removeClass('ui-state-hover');
                }).click(function(){
                    lastItem.removeClass('qw-tabs-list-current')
                    lastItem = $(this).addClass('qw-tabs-list-current');
                });
                qwin.page.tabs.listDisplay = 1;
                qwin.page.tabs.listObj.slideDown();
                event.stopPropagation();
            } else {
                qwin.page.tabs.listDisplay = 0;
                qwin.page.tabs.listObj.slideUp();
            }
        },
        showGrid: function(){
            
        },
        showHistory: function(){
            
        }
    };
});
// parseUri 1.2.2
// (c) Steven Levithan <stevenlevithan.com>
// MIT License
// @see http://stevenlevithan.com/demo/parseuri/js/
function parseUri (str) {
	var	o   = parseUri.options,
		m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
		uri = {},
		i   = 14;

	while (i--) uri[o.key[i]] = m[i] || "";

	uri[o.q.name] = {};
	uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
		if ($1) uri[o.q.name][$1] = $2;
	});

	return uri;
};

parseUri.options = {
	strictMode: "strict",
	key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
	q:   {
		name:   "queryKey",
		parser: /(?:^|&)([^&=]*)=?([^&]*)/g
	},
	parser: {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
	}
};