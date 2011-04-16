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
 * @since       2011-04-16 13:35:20
 */
jQuery(function($){
    var ul, icon;
    $('#qw-listtabs-more-link').button({
        icons: {
            secondary: "ui-icon-triangle-1-e"
        }
    });

    // 防止当鼠标移出链接时，链接变默认颜色
    $('#qw-listtabs ul li.qw-listtabs-more li').hover(function(){
        $('#qw-listtabs-more-link').addClass('ui-state-hover');
    });
    $('#qw-listtabs ul li.qw-listtabs-more').hover(
        function(){
            $(this).addClass('ui-state-hover');
            icon = $(this).find('a span.ui-icon');
            ul = $(this).find('ul');
            if ('' != $.trim(ul.html()) && 'none' == ul.css('display')) {
                icon.addClass('ui-icon-triangle-1-s');
                ul.slideDown(300);
            }
        }, function(){
            $(this).removeClass('ui-state-hover');
            if ('none' != ul.css('display')) {
                icon.removeClass('ui-icon-triangle-1-s');
                ul.slideUp(300);
            }
        }
    );
});