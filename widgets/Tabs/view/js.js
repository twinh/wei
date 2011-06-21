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
        obj: $('#qw-tabs'),
        add: function(){
            
        }
    };
    $('#qw-tabs > li > span.qw-tabs-close').click(function(){
        $(this).parent().hide(500);
    });
    qwin.page.tabs.obj.find('li').qui();
});