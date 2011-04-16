{*
 * default
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
 * @since       2011-04-15 19:21:20 v0.7.9
 *}
<div class="qw-listtabs" id="qw-listtabs">
<ul>
{foreach from=$tabs item=tab}
    <li>{Qwin_Util_JQuery::link($tab.url, $tab.title, $tab.icon, $tab.class, $tab.target, $tab.id)}<li>
{/foreach}
{if !empty($moreTab)}
    <li class="qw-listtabs-more" id="qw-listtabs-more">
        <a id="qw-listtabs-more-link">{$lang.ACT_MORE}</a>
        <ul class="ui-helper-hidden ui-state-hover ui-corner-bottom">
            {foreach from=$subTabs item=tab}
            <li>{Qwin_Util_JQuery::link($tab.url, $tab.title, $tab.icon, $tab.class, $tab.target, $tab.id)}<li>
            {/foreach}
        </ul>
    </li>
{/if}
</ul>
</div>