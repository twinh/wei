{**
 * navi
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
 * @since       2011-06-21 09:05:08
 *}
 <div class="qw-nav" id="qw-nav">
    <a class="ui-state-default ui-corner-bl" href="?{('member/my')}">{$lang.LBL_WELCOME}, {$member.username}游客!</a>
    <!--<a class="ui-state-default" href="#">{$lang.LBL_MANAGEMENT}</a>-->
    {if 'guest' == $member.username}
    <a class="ui-state-default qw-last-link qw-tabs-false" href="#">{$lang.LBL_LOGIN}</a>
    {else}
    <a class="ui-state-default qw-last-link qw-tabs-false" href="#">{$lang.LBL_LOGOUT}</a>
    {/if}
</div>