<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $config['interface']['charset'] ?>" />
<title><?php echo qw_lang('LBL_HTML_TITLE') ?></title>
<!-- qwin-packer-sign -->
<?php
$member = Qwin::run('-session')->get('member');
$nickname = isset($member['contact']) ? $member['contact']['nickname'] : $member['username'];
$jQueryFile = array(
    'core'          => $jQuery->loadUi('core', false),
    'widget'        => $jQuery->loadUi('widget', false),
    'button'        => $jQuery->loadUi('button', false),
    'coreEffect'    => $jQuery->loadEffect('core', false),
    'qui'           => $jQuery->loadPlugin('qui', null, false),
);
$minify
    ->add(QWIN_RESOURCE_PATH . '/view/style/' . $this->_style . '/jquery.ui.theme.css')
    ->add(QWIN_RESOURCE_PATH . '/view/theme/default/common/style/style.css')
    ->add($jQueryFile['core']['css'])
    ->add($jQueryFile['widget']['css'])
    ->add($jQueryFile['button']['css'])
    ->add($jQueryFile['qui']['css'])
	->add(QWIN_RESOURCE_PATH . '/image/iconx.css')
    ->add($jQuery->loadCore(false))
    ->add(QWIN_RESOURCE_PATH . '/js/qwin/qwin.js')
    ->add(QWIN_RESOURCE_PATH . '/js/qwin/url.js')
    ->add(QWIN_RESOURCE_PATH . '/view/theme/default/common/script/style.js')
    ->add($jQueryFile['core']['js'])
    ->add($jQueryFile['widget']['js'])
    ->add($jQueryFile['button']['js'])
    ->add($jQueryFile['coreEffect'])
    ->add($jQueryFile['qui']['js']);
?>
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/default/common/style/style-ie6.css" />
<![endif]-->
<script type="text/javascript">
    jQuery.noConflict();
    <?php echo qw_lang_to_js() ?>
	Qwin.get = <?php echo Qwin_Helper_Array::toJsObject($_GET);?>;
</script>
</head>
<body>
<div id="ui-main" class="ui-main ui-widget-content ui-corner-all">
  <div id="ui-header" class="ui-header ui-widget">
            <div class="ui-header-shortcut" id="ui-header-shortcut">
                <a class="ui-state-default" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Setting')) ?>"><?php echo qw_lang('LBL_WELCOME') ?>, <?php echo $nickname ?>!</a>
                <a class="ui-state-default" href="<?php echo qw_url(array('module' => 'Management', 'controller' => 'Management')) ?>"><?php echo qw_lang('LBL_MANAGEMENT') ?></a>
                <?php
                if('guest' == $member['username']):
                ?>
                <a class="ui-state-default" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Log', 'action' => 'Login')) ?>"><?php echo qw_lang('LBL_LOGIN') ?></a>
                <?php
                else :
                ?>
                <a class="ui-state-default" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Log', 'action' => 'Logout')) ?>"><?php echo qw_lang('LBL_LOGOUT') ?></a>
                <?php
                endif;
                ?>
            </div>
    <div class="ui-header-logo ui-widget-content">
        <a href="?">
            <img src="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/default/common/image/logo.png" alt="logo" />
        </a>
            </div>
    <?php $this->loadWidget('Common_Widget_NavigationBar') ?>
    </div>
<table id="ui-main-table" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td id="ui-main-left">
        <?php //Qwin_Hook::call('xxx') ?>
        <?php $this->loadWidget('Common_Widget_ViewedItem') ?>
        <?php require $this->getElement('sidebar') ?>
        </td>
        <td id="ui-main-middle" class="ui-state-default"></td>
        <td id="ui-main-right">
        <?php require $this->getElement('content') ?>
        </td>
    </tr>
</table>
</div>
<div id="ui-floating-footer" class="ui-state-default">
	<div id="ui-footer-arrow" class="ui-icon ui-icon-arrowthickstop-1-n"></div>
	<div class="ui-footer-time"></div>
    <div class="ui-copyright ui-widget"><?php echo qw_lang('LBL_FOOTER_COPYRIGHT') ?></div>
</div>
</body>
</html>
