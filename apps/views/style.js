/**
 * qwin
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
 * @since       2010-01-20 03:20:19
 */
var qwin = {
    get: {},
    app: {},
    lang: {},
    page: {},
    ajax: {}
};
jQuery(function($){
    var layout = $('body').layout({
        defaults: {
            paneClass: 'ui-widget-content',
            resizerClass: 'ui-state-default'
        },
        north: {
            resizable: false,
            slidable: false,
            togglerClass: null
        },
        south: {
            resizable: false,
            spacing_closed: 0,
            slidable: false,
            spacing_open: 0,
            togglerClass: null
        },
        west: {
            minSize: 150,
            maxSize: 200,
            spacing_closed: 23,
            togglerAlign_closed: 'top',
            togglerContent_closed: '<div id="ui-west-toggler-closed" class="ui-state-default"><span class="ui-icon ui-icon-carat-1-e"></span></div>',
            togglerLength_closed: 22
        },
        east: {
            initClosed: true,
            resizable: false,
            slidable: false,
            spacing_closed: 0
        }
    }).addCloseBtn('#ui-west-toggler-open', 'west');
    
    // 左栏打开按钮增加鼠标滑过效果
    $('#ui-west-toggler-closed').qui();
    
    // 左栏关闭按钮增加点击事件
    $('#ui-west-toggler-open').qui().click(function(){
        layout.close('west');
    });
    
    $('#west-menu').accordion({
        header: 'h3',
        icons: false
    });
    $('#west-menu li').qui();
    $('#west-menu-title').qui();
    
    // 设定元数据从data属性取值
    $.metadata.setType('attr', 'data');
    
    // 提示信息
    qwin.msg = {
        /**
         * 创建一个内联提示信息对象
         */
        create: function(opts) {
            opts = $.extend({}, qwin.msg.createDefaults, opts || {});
            var s = '<div class="qw-msg ui-corner-all ' + opts.wrapClass + '">'
                  + '   <span class="qw-msg-close ui-icon ui-icon-close"></span>'
                  + '   <div class="qw-msg-icon">'
                  + '   <span class="qw-icon ui-icon ' + opts.icon + '"></span>'
                  + '   </div>'
                  + '   <div class="qw-msg-txt">' + opts.msg + '</div>'
                  + '</div>';
            s = $(s);
            s.find('span.qw-msg-close').click(function(){
                s.hide().remove();
            });
            return s;
        },
        /**
         * 弹出信息框,可选择居中或显示在顶部等,详细参见$.blockUI
         */
        show: function(opts) {
            opts = $.extend({}, qwin.msg.defaults, opts || {});

            // 根据定位设置不同的类名
            if ('top' == opts.position) {
                opts.themedCSS.top = 0;
                opts.blockMsgClass += ' qw-msg-top';
            } else {
                opts.blockMsgClass += ' qw-msg-center';
            }
            opts.blockMsgClass += ' qw-msg-box';

            opts.message = '<div class="qw-msg-icon">' +
                '<span class="qw-icon ui-icon ' + opts.icon + '"></span>' +
                //'<span class="qw-icon qw-icon-' + icon + '-16"></span>' +
                '</div>' +
                '<div class="qw-msg-txt">' + opts.message + '</div>',
            $.blockUI(opts);
        },
        /**
         * 隐藏弹出信息框
         */
        hide: function() {
            $.unblockUI();
        },
        createDefaults: {
            icon: 'ui-icon-info',
            msg: null,
            wrapClass: 'ui-state-highlight'
        },
        defaults: {
            icon: 'ui-icon-info',
            position: 'center',
            // blockUI options
            theme: true,
            message: null,
            showOverlay: false,
            blockMsgClass: 'ui-state-highlight',
            themedCSS: {
                width: 'auto'
            }
        }
    }
    
    qwin.page = {
        left: $('#qw-left'),
        right: $('#qw-right'),
        center: $('#qw-center'),
        content: $('#qw-content'),
        splitter: $('#qw-main > td.qw-splitter'),
        fixContentHeight: function(){
            if (!document.getElementById('qw-main-table')) {
               return false;
            }
            var height = $(window).height() - $('#qw-main-table').offset().top;
            $('#qw-main-table').css('height', height);
            $(window).trigger('afterFixContentHeight');
            return true;
        }
    };
    
    // 设置全局Ajax提示信息
//    qwin.ajax.show = function(msg){
//        $('#qw-ajax').html(msg).css({
//            left: ($(window).width() - $('#qw-ajax').width()) / 2
//        }).fadeIn(200).fadeOut(2000);
//    }
//    $.ajax({
//        beforeSend: function(){
//            //qwin.ajax.show(qwin.lang.MSG_START_REQUEST);
//        },
//        error: function(){
//            qwin.ajax.show(qwin.lang.MSG_ERROR);
//        },
//        success: function(){
//            //qwin.ajax.show(qwin.lang.MSG_SUCCEEDED);
//        }
//    });

//    $.get('?module=member&action=add&view-only=content', function(data){
//          $('#qw-content').html(data);
//    });

    // 调整中间栏到最大高度
    // 修复360极速浏览器(6.0Chrome内核)高度不正确的问题
    $(window).load(function() {
        qwin.page.fixContentHeight();
    }).resize(function(){
        qwin.page.fixContentHeight();
    });

    // 点击分割栏缩进
    qwin.page.splitter.qui().click(function(){
        var id = $(this).attr('id');
        $('#qw-' + id.substring(id.lastIndexOf('-') + 1, id.length)).toggle(0, function(){
            qwin.page.splitter.trigger('toggle');
        });
    });

    // 为表单增加样式和鼠标操作效果 // input:submit, input:reset, input:button ?
    $('button.qw-button, a.qw-anchor').each(function(){
        $(this).button($(this).metadata());
    });
    $('td.qw-field-radio, td.qw-field-checkbox').buttonset();
    /*$('button.ui-button, a.ui-button').qui({
        click: true,
        focus: true
    });*/
    $('table.qw-form-table input:text, table.qw-form-table textarea, table.qw-form-table input:password').qui();

    // 点击页脚下按钮,回到顶部
    $('#qw-footer-arrow').click(function(){
        $('html').animate({scrollTop:0}, 700);
        return false;
    });
    
    $('table.ui-table:not(.ui-table-noui) tr').not('.ui-table-header').qui();
    $('table.ui-table td.ui-state-default').qui();
    $('table.ui-table td a.ui-jqgrid-icon').qui();

    //    jQuery.metadata.setType('attr', 'data');
    //var data = $('#d').metadata();
    
    /*if ($.browser.mozilla) {
        function fixSelectStyle(obj) {
            obj.attr('style', obj.find('option:selected').attr('style'));
        }
        $('select').each(function(){
             fixSelectStyle($(this));
        }).change(function(){
            fixSelectStyle($(this));
        });
    }*/
    
    /*$('#qw-header2').click(function(){
        $('#qw-header').slideToggle(0, function(){
            qwin.page.fixContentHeight();
        });
    });*/
});