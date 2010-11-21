<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_config['interface']['charset'] ?>" />
<title><?php echo qw_lang('LBL_HTML_TITLE') ?></title>
<!-- Qwin_Packer_Css -->
<!-- Qwin_Packer_Js -->
<?php
$member = Qwin::run('-session')->get('member');
$nickname = isset($member['contact']) ? $member['contact']['nickname'] : $member['username'];
$arrayHelper = Qwin::run('-arr');
$jQueryFile = array(
    'core' => $jquery->loadUi('core', false),
    'widget' => $jquery->loadUi('widget', false),
    'button' => $jquery->loadUi('button', false),
    'coreEffect' => $jquery->loadEffect('core', false),
    'qui' => $jquery->loadPlugin('qui', null, false),
    'pngFix' => $jquery->loadPlugin('pngFix', null, false),
);
$cssPacker
    ->add($jquery->loadTheme(null, false))
    ->add(QWIN_RESOURCE_PATH . '/view/theme/qwin2/style.css')
    ->add($jQueryFile['core']['css'])
    ->add($jQueryFile['widget']['css'])
    ->add($jQueryFile['button']['css'])
    ->add($jQueryFile['qui']['css']);
$jsPacker
    ->add($jquery->loadCore(false))
    ->add(QWIN_RESOURCE_PATH . '/js/qwin/qwin.js')
    ->add(QWIN_RESOURCE_PATH . '/js/qwin/url.js')
    ->add(QWIN_RESOURCE_PATH . '/view/theme/qwin2/style.js')
    ->add($jQueryFile['core']['js'])
    ->add($jQueryFile['widget']['js'])
    ->add($jQueryFile['button']['js'])
    ->add($jQueryFile['coreEffect'])
    ->add($jQueryFile['qui']['js'])
    ->add($jQueryFile['pngFix']['js']);
?>
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/qwin2/style-ie6.css" />
<![endif]-->
<script type="text/javascript">
    jQuery.noConflict();
    <?php echo qw_lang_to_js() ?>
	Qwin.get = <?php echo $arrayHelper->toJsObject($_GET);?>;
</script>
</head>
<body>
<div id="ui-top-floating-bar" class="ui-top-floating-bar ui-widget ui-widget-header">
	<ul>
    	<li class="ui-top-bar-list">
            <a href="?" class="ui-widget ui-state-active ui-corner-all"><?php echo qw_lang('LBL_QWIN') ?></a>
        </li>
        <?php
        foreach($this->adminMenu as $menu) :
            if(null == $menu['category_id']) :
        ?>
        <li class="ui-top-bar-list">
            <a href="<?php echo $menu['url'] ?>" target="<?php echo $menu['target'] ?>" class="ui-widget ui-state-default ui-corner-all"><?php echo $menu['title'] ?></a>
            <ul class="ui-state-hover ui-corner-bottom ui-corner-tr">
                <?php
                foreach($this->adminMenu as $subMenu) :
                    if($menu['id'] == $subMenu['category_id']) :
                ?>
                <li><a href="<?php echo $subMenu['url'] ?>" target="<?php echo $subMenu['target'] ?>"><?php echo $subMenu['title'] ?></a></li>
                <?php
                    endif;
                endforeach;
                ?>
            </ul>
        </li>
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
            <img src="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/qwin2/image/logo.gif" alt="logo" />
            <img src="<?php echo QWIN_RESOURCE_PATH ?>/view/theme/qwin2/image/common.gif" alt="logo" />
        </a>
    </div>
  </div>
<table id="ui-main-table">
    <tr>
        <td id="ui-main-left">
        <div class="ui-siderbar ui-box ui-widget ui-widget-content ui-corner-all">
            <div class="ui-sidebar-titlebar ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix" id="ui-sidebar-title-1">
                <span class="ui-box-title"><?php echo qw_lang('LBL_LAST_VIEWED_ITEM') ?></span>
                <a class="ui-sidebar-title-icon ui-box-title-icon ui-corner-all" name="#ui-sidebar-content-last" href="javascript:void(0)">
                    <span class="ui-icon ui-icon-circle-triangle-n">open/close</span>
                </a>
            </div>
            <div class="ui-box-content ui-widget-content">
                <ul id="ui-sidebar-content-last">
                <?php
                if(empty($lastViewedItem)):
                ?>
                    <li class="ui-sidebar-list ui-widget-content"><a><?php echo qw_lang('MSG_NO_LAST_VIEWED_LOG') ?></a></li>
                <?php
                else:
                    foreach($lastViewedItem as $row):
                ?>
                    <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo $row['href'] ?>"><?php echo $row['title'] ?></a></li>
                <?php
                    endforeach;
                endif;
                ?>
                </ul>
            </div>
        </div>
        </td>
        <td id="ui-main-right">
<?php
require $this->getElement('content');
?>
        </td>
    </tr>
</table>
  <div class="ui-footer ui-widget">
    <div class="ui-copyright ui-widget-content"><?php echo qw_lang('LBL_FOOTER_COPYRIGHT') ?></div>
  </div>
</div>
</body>
</html>
