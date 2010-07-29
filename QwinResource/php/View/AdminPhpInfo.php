<?php
/**
 * default 的名称
 *
 * default 的简要介绍
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
 * @version   2009-10-31 01:19:12 utf-8 中文
 * @since     2009-11-24 18:47:32 utf-8 中文
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<style type="text/css">
pre {margin: 0px; font-family: monospace;}
table {border-collapse: collapse;}
.center {text-align: center; margin-top: 2em;}
.center table { margin-left: auto; margin-right: auto; text-align: left;}
.center th { text-align: center !important; }
td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold; color: #000000;font-size: 1.2em;}
.h {background-color: #9999cc; font-weight: bold; color: #000000;font-size: 1.2em;}
.v {background-color: #cccccc; color: #000000;font-size: 1.2em;}
.vr {background-color: #cccccc; text-align: right; color: #000000;font-size: 1.2em;}
img {float: right; border: 0px;}
hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
</style>
<div class="ui-list ui-box ui-widget ui-widget-content ui-corner-all">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"> <span class="ui-box-title"><a href="<?php echo url(array('admin', $this->__query['controller'])); ?>">系统信息</a></span> <a class="ui-box-title-icon ui-corner-all" name=".ui-list-content" href="javascript:void(0)"><span class="ui-icon  ui-icon-circle-triangle-n">open/close</span></a> </div>
    <div class="ui-list-content ui-box-content ui-widget-content">
        <?php echo $php_info?>
    </div>
</div>