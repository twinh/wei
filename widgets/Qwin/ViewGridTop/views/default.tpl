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
 * @todo        列表data属性代码复杂,难以认清,需简化
 *}
{strip}
<div class="qw-listtabs">
<ul class="ui-widget-content qw-listtabs-parent">
{foreach from=$tabs item=tab}
    <li><a class="qw-anchor" href="{$tab.url}" id="{$tab.id}" class="{$tab.class}" target="{$tab.target}" data="{literal}{icons:{primary:'{/literal}{$tab.icon}{literal}'}}{/literal}">{$tab.title}</a></li>
{/foreach}
{if !empty($moreTab)}
    <li class="qw-listtabs-more" id="qw-listtabs-more">
        <a class="qw-listtabs-more-link">{$lang.ACT_MORE}</a>
        <ul class="ui-helper-hidden ui-state-hover ui-corner-bottom">
        {foreach from=$subTabs item=tab}
        <li><a class="qw-anchor" href="{$tab.url}" id="{$tab.id}" class="{$tab.class}" target="{$tab.target}" data="{literal}{icons:{primary:'{/literal}{$tab.icon}{literal}'}}{/literal}">{$tab.title}</a></li>
        {/foreach}
        </ul>
    </li>
{/if}
</ul>
</div>
{/strip}