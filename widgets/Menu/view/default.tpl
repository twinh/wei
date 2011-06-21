{**
 * default
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
 * @since       2011-06-20 18:31:59
 *}
<div class="qw-shortcut ui-state-default">
    <a class="qw-icon qw-icon-monitor-16" href="javascript:;">{$lang.LBL_SHORTCUT}</a>
</div>
<ul class="qw-menu">
    {foreach from=$menus[0] item=menu}
    <li>
        <a class="qw-icon qw-icon-document-16" href="{$menu.url}" target="{$menu.target}">{$menu.title}</a>
    {if isset($menus[1][$menu.id])}
    <ul class="ui-state-hover ui-corner-right">
        {foreach from=$menus[1][$menu.id] item=subMenu}
        <li><a href="{$subMenu.url}" target="{$subMenu.target}">{$subMenu.title}</a></li>
        {/foreach}
    </ul>
    {/if}
    </li>
    {/foreach}
</ul>