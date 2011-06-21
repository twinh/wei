{*
 * navigation-bar
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
 * @since       2011-01-03 01:17:46
 *}
{strip}
<div class="qw-c"></div>
<div class="qw-tabs-box">
    <ul id="qw-tabs">
        <li class="ui-widget ui-state-active ui-corner-top">
            <a href="{$menus.qwin.url}">{$menus.qwin.title}</a>
        </li>
        {foreach from=$menus[0] item=menu}
        <li class="ui-widget ui-state-default ui-corner-top">
            <span class="qw-tabs-close ui-icon ui-icon-close"></span>
            <a href="{$menu.url}" target="{$menu.target}">{$menu.title}</a>
        </li>
        {/foreach}
    </ul>
</div>
{strip}
