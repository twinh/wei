<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo qw_lang('LBL_HTML_TITLE') ?></title>
<!--{JS}-->
<!--{CSS}-->
<?php
/*
$ses = Qwin::run('-ses');
$loginState = $ses->get('member');
$qurl = array(
    'nca' => qw('-url')->nca,
    'separator' => qw('-url')->separator,
    'extension' => qw('-url')->extension,
);*/
$qurl = null;
?>

<link rel="stylesheet" type="text/css" href="../resource/view/theme/qwin2/style.css" />
<?php

$jquery = Qwin::run('-jquery');

echo $jquery->loadTheme(),
    $jquery->loadCore(),
    $jquery->loadUi('core'),
    $jquery->loadUi('widget'),
    $jquery->loadUi('button'),
    $jquery->loadEffect('core'),
    $jquery->loadPlugin('qui'),
    $jquery->loadPlugin('jqgrid');
?>
<script type="text/javascript" src="../resource/js/qwin/common.js"></script>
<script type="text/javascript" src="../resource/js/qwin/qwin.js"></script>
<script type="text/javascript" src="../resource/js/qwin/url.js"></script>
<script type="text/javascript" src="../resource/js/jquery/plugin/jqgrid/i18n/grid.locale-en.js"></script>
<script type="text/javascript" src="../resource/view/theme/qwin2/style.js"></script>
</head>
<body>
<script type="text/javascript">
    jQuery.noConflict();
    <?php echo qw_lang_to_js() ?>
	var _get = <?php echo $arrayHelper->toJsObject($_GET);?>;
    var qurl = <?php echo $arrayHelper->toJsObject($qurl);?>;
    jQuery(function($) {
		
    });
</script>
<div id="ui-top-floating-bar" class="ui-top-floating-bar ui-widget ui-widget-header">
	<ul>
    	<li><a class="ui-anchor ui-state-active" href="#Qwin">Qwin</a></li>
        <li><a class="ui-anchor" href="#Article">Home</a></li>
        <li><a class="ui-anchor" href="#Article">Article</a></li>
        <li><a class="ui-anchor" href="#Picture">Picture</a></li>
        <li><a class="ui-anchor" href="#Product">Product</a></li>
        <li><a class="ui-anchor" href="#File Manager">File Manager</a></li>
        <li><a class="ui-anchor" href="#Administrator">Administrator</a></li>
        <li><a class="ui-anchor" href="#Administrator">Administrator</a></li>
        <li><a class="ui-anchor" href="#Article">Add Your Link</a></li>
        <li><a class="ui-anchor" href="#Article"><span class="ui-icon ui-icon-carat-1-w"></span></a></li>
        <li><a class="ui-anchor" href="#Article"><span class="ui-icon ui-icon-carat-1-e"></span></a></li>
        <li><a class="ui-anchor" href="#Article"><span class="ui-icon ui-icon-close"></span></a></li>
    </ul>
</div>
<div id="ui-bottom-floating-botton" class="ui-bottom-floating-botton"><a class="ui-anchor" href="#"><span class="ui-icon ui-icon-arrowthickstop-1-n"></span></a></div>
<div id="ui-main" class="ui-main ui-widget-content ui-corner-all">
  <div id="ui-header" class="ui-header ui-widget">
    <div class="ui-header-shortcut" id="ui-header-shortcut">
    	<a class="ui-state-default" href="#"><?php echo qw_lang('LBL_WELCOME') ?>, Twin!</a>
        <a class="ui-state-default" href="?namespace=Default&module=Member&controller=Log&action=Logout"><?php echo qw_lang('LBL_LOGOUT') ?></a>
    </div>
    <div class="ui-header-logo ui-widget-content"> <a href=""><?php echo qw_lang('LBL_QWIN') ?><sup><?php echo qw_lang('LBL_QWIN_VERSION') ?></sup></a> </div>
  </div>
  <script type="text/javascript">
var validator_rule = {"title":{"required":true,"maxlength":200}};

</script>
<?php
require $this->getElement('content');
?>
  <div class="ui-footer ui-widget">
    <div class="ui-copyright ui-widget-content"><?php echo qw_lang('LBL_FOOTER_COPYRIGHT') ?></div>
  </div>
</div>
</body>
</html>
