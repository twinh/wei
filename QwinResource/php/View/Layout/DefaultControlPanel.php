<?php
/**
 * header 的名称
 *
 * header 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-11-20 01:12:01
 * @since     2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->t('LBL_HTML_TITLE')?></title>
<!--{JS}-->
<!--{CSS}-->
<?php
$rsc = Qwin::run('-rsc');
$rsc->load('js/jquery/core/jquery')
    ->load('jquery/ui/core')
    ->load('jquery/theme/' . qw('Qwin_Hepler_Util')->getStyle())
    ->load('js/other/common')
    ->load('css/style')
    ->load('js/other/qwin')
    ->load('js/other/url')
    ->load('js/other/adjust_width')
    ->load('jquery/plugin/qui')
    ->load('jquery/ui/tabs');

$ses = Qwin::run('-ses');
$loginState = $ses->get('member');
$qurl = array(
    'nca' => qw('-url')->nca,
    'separator' => qw('-url')->separator,
    'extension' => qw('-url')->extension,
);
$menu = require_once ROOT_PATH . '/Cache/Php/List/AdminMenu.php';
?>
</head>
<body>
<script type="text/javascript">
    jQuery.noConflict();
    var _get = <?php echo qw('-arr')->toJsObject(qw('-url')->getGetArray());?>;
    var qurl = <?php echo qw('-arr')->toJsObject($qurl);?>;
    jQuery(function($) {
        $("#ui-navigation-bar").tabs({
            event: 'mouseover'
        });
        $('div.ui-navigation-bar .ui-tabs-panel a').qui();
        c();
        function c()
        {
            $('body')
                .append('<div id="ui-temp-widget-tcontent" class="ui-widget-content"></div>')
                .css('background-color', $('#ui-temp-widget-tcontent').css('background-color'));
            //alert($('#ui-temp-widget-tcontent').css('background-color'))
            $('#ui-temp-widget-tcontent').remove();
        }
        $('#ui-sidebar-content-tool li, #ui-sidebar-content-shortcut li').qui();
    });
</script>
<div id="ui-header" class="ui-header ui-widget">
        <div class="ui-header-shortcut ui-widget-content">
            <a href="#"><?php echo $this->t('LBL_WELCOME')?>, <?php echo $loginState['username']?>!</a>
            <a href="<?php echo url(array($this->__query['namespace'], 'Member', 'Log', 'Logout'))?>">[<?php echo $this->t('LBL_LOGOUT')?>]</a>
        </div>
        <div class="ui-header-logo ui-widget-content">
            <a href="">Qwin</a>
        </div>
</div>
<div id="ui-navigation-bar" class="ui-navigation-bar">
    <ul>
<?php
foreach($menu as $key => $val){
    if(null == $val['category_id']){
?>
        <li><a href="#menu-<?php echo $val['id']?>"><?php echo $val['title']?></a></li>
<?php
    }
}
?>
    </ul>
<?php
foreach($menu as $key => $val){
    if(0 == $val['category_id'])
    {
?>
    <div id="menu-<?php echo $val['id']?>">
<?php
        foreach($menu as $key2 => $val2){
           if($val['id'] == $val2['category_id']){
?>
        <a href="<?php echo $val2['url']?>"><?php echo $val2['title']?></a>
<?php
           }
        }
?>
    </div>
<?php
    }
}
?>
</div>
<div id="ui-main" class="ui-main">
    <div id="ui-main-leftbar" class="ui-main-leftbar">
        <!-- TODO tool(calender,world clock,calculator,page setting) -->
        <div class="ui-siderbar ui-box ui-widget ui-widget-content ui-corner-all">
            <div class="ui-sidebar-titlebar ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix" id="ui-sidebar-title-1">
                <span class="ui-box-title"><?php echo $this->t('LBL_TOOL')?></span>
                <a class="ui-sidebar-title-icon ui-box-title-icon ui-corner-all" name="#ui-sidebar-content-tool" href="javascript:void(0)">
                    <span class="ui-icon ui-icon-circle-triangle-n">open/close</span>
                </a>
            </div>
            <div class="ui-box-content ui-widget-content">
                <ul id="ui-sidebar-content-tool">
                    <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo url(array($this->__query['namespace'], 'Member', 'Setting', 'SwitchStyle'))?>"><?php echo $this->t('LBL_STYLE')?></a></li>
                    <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo url(array($this->__query['namespace'], 'Member', 'Setting', 'SwitchLang'))?>"><?php echo $this->t('LBL_LANG')?></a></li>
                </ul>
            </div>
        </div>
        <div class="ui-siderbar ui-box ui-widget ui-widget-content ui-corner-all">
            <div class="ui-sidebar-titlebar ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix" id="ui-sidebar-title-1">
                <span class="ui-box-title"><?php echo $this->t('LBL_SHORTCUT')?></span>
                <a class="ui-sidebar-title-icon ui-box-title-icon ui-corner-all" name="#ui-sidebar-content-shortcut" href="javascript:void(0)">
                    <span class="ui-icon ui-icon-circle-triangle-n">open/close</span>
                </a>
            </div>
            <div class="ui-box-content ui-widget-content">
                <ul id="ui-sidebar-content-shortcut">
<?php
if(isset($this->__meta['db']['table'])){
?>
                    <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller']))?>" target="_self"><?php echo $this->t('LBL_ACTION_LIST')?></a></li>
                    <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], 'Add'))?>" target="_self"><?php echo $this->t('LBL_ACTION_ADD')?></a></li>
<?php
}
if(isset($this->__meta['shortcut'])){
foreach($this->__meta['shortcut'] as $shortcut){
?>
                    <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo $shortcut['link']?>" target="_self"><?php echo $this->t($shortcut['name'])?></a></li>
<?php
}}
?>
                </ul>
            </div>
        </div>
        <!-- TODO last viewed -->
    </div>
    </div>
<div id="ui-main-content" class="ui-main-content">
<?php
require_once $this->loadViewElement('content');
?>
</div>
<div class="ui-footer ui-widget">
    <div class="ui-copyright ui-widget-content"><?php echo $this->t('LBL_FOOTER_COPYRIGHT')?></div>
</div>
</body>
</html>
