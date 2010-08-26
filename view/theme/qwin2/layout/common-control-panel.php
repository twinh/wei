<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo qw_lang('LBL_HTML_TITLE') ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/qwin2/style.css" />
<?php
/*
$ses = Qwin::run('-ses');
$loginState = $ses->get('member');
 */
$qurl = null;
$jquery = Qwin::run('-jquery');
echo $jquery->loadTheme(),
    $jquery->loadCore(),
    $jquery->loadUi('core'),
    $jquery->loadUi('widget'),
    $jquery->loadUi('button'),
    $jquery->loadEffect('core'),
    $jquery->loadPlugin('qui')
    ;
?>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH ?>/js/qwin/common.js"></script>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH ?>/js/qwin/qwin.js"></script>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH ?>/js/qwin/url.js"></script>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/qwin2/style.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
    <?php echo qw_lang_to_js() ?>
	Qwin.get = <?php echo $arrayHelper->toJsObject($_GET);?>;
</script>
</head>
<body>
<div id="ui-top-floating-bar" class="ui-top-floating-bar ui-widget ui-widget-header">
	<ul>
    	<li><a class="ui-anchor ui-state-active" href="?"><?php echo qw_lang('LBL_QWIN') ?></a></li>
        <?php
        foreach($this->adminMenu as $menu) :
            if(null == $menu['category_id']):
        ?>
        <li><a class="ui-anchor" href="<?php echo $menu['url'] ?>"><?php echo $menu['title'] ?></a></li>
        <?php
            endif;
        endforeach;
        ?>
    </ul>
</div>
    <div id="ui-bottom-floating-botton" class="ui-bottom-floating-botton"><button><span class="ui-icon ui-icon-arrowthickstop-1-n"></span></button></div>
<div id="ui-main" class="ui-main ui-widget-content ui-corner-all">
  <div id="ui-header" class="ui-header ui-widget">
    <div class="ui-header-shortcut" id="ui-header-shortcut">
    	<a class="ui-state-default" href="?"><?php echo qw_lang('LBL_WELCOME') ?>, Twin!</a>
        <a class="ui-state-default" href="?namespace=Default&module=Member&controller=Log&action=Logout"><?php echo qw_lang('LBL_LOGOUT') ?></a>
    </div>
    <div class="ui-header-logo ui-widget-content"> <a href="?"><?php echo qw_lang('LBL_QWIN') ?><sup><?php echo qw_lang('LBL_QWIN_VERSION') ?></sup></a> </div>
  </div>
<?php
require $this->getElement('content');
?>
  <div class="ui-footer ui-widget">
    <div class="ui-copyright ui-widget-content"><?php echo qw_lang('LBL_FOOTER_COPYRIGHT') ?></div>
  </div>
</div>
</body>
</html>
