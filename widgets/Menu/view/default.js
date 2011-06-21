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
 * @since       2011-06-20 19:00:27
 */
jQuery(function($){
    // 鼠标移过菜单栏,显示子菜单
    $('#qw-left-content ul.qw-menu li').hover(function(){
        var _this = $(this);
        _this.find('ul:first').css({
            left: _this.width()
        }).show();
        $(this).css('padding', '0').addClass('ui-state-hover');
    }, function(){
        $(this).find('ul:first').hide();
        $(this).css('padding', '1px').removeClass('ui-state-hover');
    }).find('a').click(function(){
        $.ajax({
            url: $(this).attr('href'),
            data: 'view=content',
            cache: false,
            success: function(msg){
                qwin.page.middle.html(msg);
            }
        });
        return false;
    });
});