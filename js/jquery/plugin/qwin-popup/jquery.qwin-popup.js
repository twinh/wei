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
// 需jquery dialog, jqgrid
(function($) {
    $.fn.qwinPopup = function(options) {
        var opts = $.extend({}, $.fn.qwinPopup.defaults, options);
        // 设置窗口标题
        if(null != opts.title)
        {
            opts.dialog.title = opts.title;
        }

        // 方便外部调用
        $.popupOpts = opts;

        // 点击弹出窗口,显示内容
        this.click(function(){
            if($('#ui-popup').html() == null)
			{
				$('body').append('<div id="ui-popup"></div>');
			}
            var popupObj = $('#ui-popup');
            $.popupOpts.obj = popupObj;
            popupObj.load(
                opts.url,
                {},
                function (){
					popupObj.dialog(opts.dialog);
                }
            );
        });
        return this;
    };

    $.fn.qwinPopup.defaults = {
        title: null,
        url: 'htt://www.google.com',
        // 表单域
        viewInput: null,
        valueInput: null,
        // Pop窗口栏
        viewColumn: 'name',
        valueColumn: 'id',
        dialog: {
            height: 'auto',
            width: 'auto',
            modal: true,
            dialogClass: 'ui-popup-content'
        }
    }
})(jQuery);
