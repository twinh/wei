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
 * @version   2009-11-20 01:12:01 utf-8 中文
 * @since     2009-11-24 18:47:32 utf-8 中文
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Content Management System - Powered by QWin Framework</title>
<!--{JS}-->
<!--{CSS}-->
<?php
qw('-rsc')->load('js/jquery/core/jquery');
qw('-rsc')->load('jquery/ui/core');
qw('-rsc')->load('jquery/theme/' . $_GET['style']);
qw('-rsc')->load('js/other/common');
qw('-rsc')->load('css/admin');
qw('-rsc')->load('js/other/qwin');
qw('-rsc')->load('js/other/url');
qw('-rsc')->load('js/other/adjust_width');

$is_login = qw('-acl')->isLogin();
$qurl = array(
	'nca' => qw('-url')->nca,
	'separator' => qw('-url')->separator,
	'extension' => qw('-url')->extension,
);
?>
<script type="text/javascript">
//jQuery.noConflict();
var _get = <?php echo qw('-arr')->toJsObject(qw('-url')->getGetArray());?>;
var qurl = <?php echo qw('-arr')->toJsObject($qurl);?>;
</script>
<!--<script type="text/javascript" src="../resource/js/prototype/prototype.js"></script>-->
</head>
<body>
