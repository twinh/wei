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
<script type="text/javascript">
jQuery(function($){
    qwin.page.tabs.data = {json_encode(${tabs})};
    qwin.page.tabs.lastId = '{$lastTab}';
    qwin.page.tabs.init();
});
</script>
{strip}
<div class="qw-c"></div>
<div class="qw-tabs-box">
    <ul id="qw-tabs-oper">
        <li id="qw-tabs-prev" class="ui-button-none ui-state-default ui-corner-tl">
            <span class="ui-icon ui-icon-triangle-1-w"></span>
        </li>
        <li id="qw-tabs-next" class="ui-button-none ui-state-default">
            <span class="ui-icon ui-icon-triangle-1-e"></span>
        </li>
        <li id="qw-tabs-list" class="ui-button-none ui-state-default">
            <span class="ui-icon ui-icon-triangle-1-s"></span>
            <ul class="ui-state-default ui-corner-bottom"></ul>
        </li>
        <!-- TODO history <li id="qw-tabs-closed" class="ui-button-none ui-state-default">
            <span class="ui-icon ui-icon-arrowreturnthick-1-w"></span>
            <ul class="ui-state-default ui-corner-bottom"></ul>
        </li> -->
        <!-- TODO grid <li id="qw-tabs-grid" class="ui-button-none ui-state-default">
            <span class="ui-icon ui-icon-calculator"></span>
        </li> -->
    </ul>
    <ul id="qw-tabs"></ul>
</div>
{strip}