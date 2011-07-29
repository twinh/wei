/**
 * jquery.qwin-popup.js
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
 * @since       2010-10-04 0:13:44
 * @todo        理清过程,标准化插件
 */
// 需jQuery dialog, jqgrid
(function($) {
    $.fn.popupGrid = function(options) {
        var opts = $.extend({}, $.fn.popupGrid.defaults, options);
        opts.id = $.fn.popupGrid.id;
        $.fn.popupGrid.id++;
        
        // 设置窗口标题
        if (null != opts.title) {
            opts.dialog.title = opts.title;
        }
        
        // 点击弹出窗口,显示内容
        this.click(function(){
            if (0 != $('#qw-popup-iframe-' + opts.id).length) {
                opts.obj.dialog('open');
                return;
            }
            opts.obj = $.tmpl($.fn.popupGrid.iframeTmpl, {
                id: opts.id,
                url: opts.url
            })
            qwin.msg.show({
                message: 'loading...'
            });
            opts.obj
            .dialog(opts.dialog)
            .find('iframe').load(function(){
                // 双击赋值
                var jqGridObj = window.frames['qw-popup-iframe-' + opts.id].qwin.jqGrid;
                jqGridObj.jqGrid('setGridParam', {
                    ondblClickRow: function(rowId, iRow, iCol, e){
                        var rowData = jqGridObj.jqGrid('getRowData', rowId);
                        $(opts.valueInput).val(rowData[opts.valueColumn]);
                        $(opts.viewInput).val(rowData[opts.viewColumn] + '(' + qwin.lang.LBL_SELECTED + ', ' + qwin.lang.LBL_READONLY + ')');
                        opts.obj.dialog('close');
                    }
                    // TODO 动态设置高度
                    /*,
                    gridComplete: function(){
                        opts.obj.dialog('option', 'height', '470');
                    }*/
                });
                qwin.msg.hide();
            });
            this.blur();
        });
        return this;
    };

    $.fn.popupGrid.defaults = {
        title: null, // 弹出对话框显示的标题
        url: null, // 弹出对话框内容的地址
        viewInput: null, // 显示选中数据的输入框
        valueInput: null, // 显示选中数据的值的输入框
        viewColumn: 'name', // 弹出窗口的列表域名称
        valueColumn: 'id', // 弹出窗口的列表域的值
        dialog: { // jQuery 对话框的配置
            position: ['center', 60],
            height: 470,
            width: 800,
            modal: true,
            dialogClass: 'qw-popup'
        }
    }
    $.fn.popupGrid.id = 1;
    $.fn.popupGrid.iframeTmpl = '<div><iframe name="qw-popup-iframe-${id}" id="qw-popup-iframe-${id}" class="qw-popup-iframe" frameborder="no" scrolling="auto" src="${url}"></iframe></div>';
})(jQuery);
