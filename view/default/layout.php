<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_options['charset'] ?>" />
<title><?php echo $lang['LBL_HTML_TITLE'] ?></title>
<?php
echo $this->getPackerSign();
$member = Qwin::call('-session')->get('member');
$nickname = isset($member['contact']) ? $member['contact']['nickname'] : $member['username'];
$minify->addArray(array(
    $style->getCssFile(),
    $this->getTag('root') . 'style.css',
    $config['resource'] . 'view/default/icons/icon.css',
    $jQuery->loadCore(false),
    $jQuery->loadUi('core', false),
    $jQuery->loadUi('widget', false),
    $jQuery->loadUi('button', false),
    $jQuery->loadEffect('core', false),
    $jQuery->loadPlugin('qui', null, false),
    $this->getTag('root') . 'style.js',
    //$this->getTag('root') . 'DD_roundies_0.0.2a-min.js',
));
?>
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->getTag('root') ?>style-ie6.css" />
<![endif]-->
<script type="text/javascript">
    qwin.get = <?php echo json_encode($_GET) ?>;
    qwin.lang = <?php echo json_encode($lang->toArray()) ?>;
</script>
<!--<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>-->
</head>
<body>
<div id="qw-main" class="ui-widget-content">
<table id="qw-header" class="ui-widget" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td id="qw-header-left">
            <div id="qw-logo" class="ui-widget-content">
                <a href="?">
                    <img src="<?php echo $this->getTag('root') ?>com/image/logo.png" alt="logo" />
                </a>
            </div>
        </td>
        <td id="qw-header-right" colspan="2">
            <div class="qw-header-shortcut" id="qw-header-shortcut">
                <a class="ui-state-default ui-corner-bl" href="<?php echo qw_u('com/member/my') ?>"><?php echo qw_t('LBL_WELCOME') ?>, <?php echo $nickname ?>!</a>
                <a class="ui-state-default" href="#"><?php echo qw_t('LBL_MANAGEMENT') ?></a>
                <?php
                if('guest' == $member['username']):
                ?>
                <a class="ui-state-default qw-last-link" href="<?php echo qw_u('com/member/auth', 'login') ?>"><?php echo qw_t('LBL_LOGIN') ?></a>
                <?php
                else :
                ?>
                <a class="ui-state-default qw-last-link" href="<?php echo qw_u('com/member/auth', 'logout') ?>"><?php echo qw_t('LBL_LOGOUT') ?></a>
                <?php
                endif;
                ?>
            </div>
            <div class="qw-c"></div>
            <?php Qwin::hook('ViewNaviBar') ?>
        </td>
    </tr>
</table>
<div id="qw-header2" class="ui-widget-content ui-state-default"></div>
<table id="qw-content-table" border="0" cellpadding="0" cellspacing="0">
    <tr id="qw-content">
        <td id="qw-left">
            <div id="qw-left-content">
                <?php require $this->getElement('sidebar') ?>
            </div>
        </td>
        <td id="qw-splitter-left" class="qw-splitter ui-state-default">
            <div class="qw-splitter-content"></div>
        </td>
        <td id="qw-middle">
            <?php require $this->getElement('content') ?>
        </td>
        <td id="qw-splitter-right" class="qw-splitter ui-state-default">
            <div class="qw-splitter-content"></div>
        </td>
        <td id="qw-right" class="ui-helper-hidden">
            <div id="qw-right-content"></div>
        </td>
    </tr>
</table>
</div>
<div id="qw-footer" class="ui-state-default">
    <div id="qw-footer-arrow" class="ui-icon ui-icon-arrowthickstop-1-n"></div>
    <div id="qw-footer-time"></div>
    <div id="qw-copyright" class="ui-widget">Executed in <?php echo $widget->call('app')->getEndTime() ?>(s). <?php echo qw_t('LBL_FOOTER_COPYRIGHT') ?></div>
</div>
</body>
</html>
