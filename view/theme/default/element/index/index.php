<?php
/**
 * index
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
 * @package     Trex
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-31 11:52:12
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<style type="text/css">
#ui-home-table{
	margin: 10px 0;
}
#ui-home-table tr td{
	padding: 5px;
}
#ui-home-table .ui-box{
	height: 240px;
}
#ui-home-table .ui-box-title{
	font-weight: bold;
}
#ui-home-table .ui-box-content{
	margin: 4px;
}
#ui-home-table .ui-box-content p{
	line-height: 20px;
}
.red{
	color: red;
}
.blue{
	color: blue;
}
.green{
	color: green;
}
.ui-home-input{
	height: 27px;
}
.ui-home-td-1 .ui-button{
	margin: 4px 2px;
}
.ui-home-list li{
	margin: 4px;
}
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <?php echo qw_lang('LBL_HOME')?>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <table width="100%" id="ui-home-table">
        	<tr>
            	<td width="33%" valign="top">
                <div class="ui-box ui-widget ui-widget-content ui-corner-all">
                <div class="ui-box-titlebar ui-state-default ui-corner-tl ui-corner-tr ui-helper-clearfix">
                    <span class="ui-box-title">Welcome</span>
                </div>
                <div class="ui-box-content ui-home-td-1">
                	<p>Welcome to login the system, <span class="green"><?php echo $member['contact']['nickname'] ?></span>.</p>
                    <p>
                    <br />
                    <?php  echo qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'View', 'id' => $member['id'])), 'View Data', 'ui-icon-lightbulb'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'Edit', 'id' => $member['id'])), 'Edit Data', 'ui-icon-tag'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'EditPassword', 'id' => $member['id'])), 'Edit Password', 'ui-icon-key'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'LoginLog', 'searchField' => 'member_id', 'searchValue' => $member['id'])), 'Login Log', 'ui-icon-script'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Setting', 'action' => 'SwitchStyle')), 'Switch Style', 'ui-icon-calculator'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Setting', 'action' => 'SwitchLanguage')), 'Switch Language', 'ui-icon-script') ?>
                     </p>
                </div>
                </div>
                </td>
                <td width="33%" valign="top">
                <div class="ui-box ui-widget ui-widget-content ui-corner-all">
                <div class="ui-box-titlebar ui-state-default ui-corner-tl ui-corner-tr ui-helper-clearfix">
                    <span class="ui-box-title">Login Log</span>
                </div>
                <div class="ui-box-content">
                	<p>The last time you login in the system:</p>
                	<p>1.The time is <span class="green"><?php echo $loginLog['date_created'] ?></span></p>
                    <p>2.The ip address is <span class="green"><?php echo $loginLog['ip'] ?></span>
                    	<a href="http://www.ip138.com/ips.asp?ip=<?php echo $loginLog['ip'] ?>" target="_blank"><strong>Look up</strong></a>
                    </p>
                </div>
                </div>
                </td>
                <td width="33%" valign="top">
                <div class="ui-box ui-widget ui-widget-content ui-corner-all">
                <div class="ui-box-titlebar ui-state-default ui-corner-tl ui-corner-tr ui-helper-clearfix">
                    <span class="ui-box-title">Weather</span>
                </div>
                <div class="ui-box-content">
                	<IFRAME WIDTH='200' HEIGHT='192' ALIGN='CENTER' MARGINWIDTH='0' MARGINHEIGHT='0' HSPACE='0' VSPACE='0' FRAMEBORDER='0' SCROLLING='NO' SRC='http://weather.qq.com/inc/ss1.htm'></IFRAME>
                </div>
                </div>
                </td>
            </tr>                         
            <tr>
                <td width="33%" valign="top">
                <div class="ui-box ui-widget ui-widget-content ui-corner-all">
                <div class="ui-box-titlebar ui-state-default ui-corner-tl ui-corner-tr ui-helper-clearfix">
                    <span class="ui-box-title">New Article</span>
                </div>
                <div class="ui-box-content ui-home-list">
                	<ul>
                    <?php
					foreach($articleData as $row):
						$row['date_created'] = date('Y-m-d', strtotime($row['date_created']));
					?>
                    	<li><span>[<?php echo $row['date_created'] ?>]</span><a href="?module=Article&controller=Article&action=View&id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></li>
                    <?php
					endforeach;
					?>
                    </ul>
                </div>
                </div>
                </td>
                <td>
                <div class="ui-box ui-widget ui-widget-content ui-corner-all">
                <div class="ui-box-titlebar ui-state-default ui-corner-tl ui-corner-tr ui-helper-clearfix">
                    <span class="ui-box-title">New Feedback</span>
                </div>
                <div class="ui-box-content ui-home-list">
                	<ul>
                    <?php
					foreach($feedbackData as $row):
						$row['date_created'] = date('Y-m-d', strtotime($row['date_created']));
					?>
                    	<li><span>[<?php echo $row['date_created'] ?>]</span><a href="?module=Feedback&controller=Feedback&action=View&id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></li>
                    <?php
					endforeach;
					?>
                    </ul>
                    </ul>
                </div>
                </div>
                </td>
                <td>
                <div class="ui-box ui-widget ui-widget-content ui-corner-all">
                <div class="ui-box-titlebar ui-state-default ui-corner-tl ui-corner-tr ui-helper-clearfix">
                    <span class="ui-box-title">Custom Search</span>
                </div>
                <div class="ui-box-content">
                	<p style="margin:8px;">
                	<!-- Use of this code assumes agreement with the Google Custom Search Terms of Service. -->
                    <!-- The terms of service are available at http://www.google.com/cse/docs/tos.html?hl=en-US -->
                    <form action="http://www.google.com.hk/search?" target="_blank">
                      <input type="hidden" name="ie" value="utf-8" />
                      <input type="hidden" name="hl" value="en-US" />
                      <input name="q" type="text" size="15" class="ui-home-input" />
                      <input type="submit" name="sa" value="Google Search" />
                    </form>
                    </p>
                    <p style="margin:8px;">
                    <form action="http://www.baidu.com/baidu" target="_blank">
                    <input name=tn type=hidden value=baidu>
                    <input type=text name=word size="15" class="ui-home-input">
                    <input type="submit" value="Baidu Search">
                    </form>
                    </p>
                </div>
                </div>
                </td>
            </tr>
        </table>
    </div>
</div>