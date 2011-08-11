<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_options['charset'] ?>" />
<title><?php echo $lang['LBL_HTML_TITLE'] ?></title>
<?php
echo $this->getPackerSign();
$minify->addArray(array(
    $style->getCssFile(),
    $this->getTag('root') . 'style.css',
    $config['resource'] . 'view/default/icons/icon.css',
    $jQuery->loadCore(false),
    $jQuery->loadUi('core', false),
    $jQuery->loadUi('widget', false),
    $jQuery->loadUi('button', false),
    $jQuery->loadUi('position', false),
    $jQuery->loadEffect('core', false),
    $jQuery->loadPlugin('qui', null, false),
    $jQuery->loadPlugin('blockUI', null, false),
    $jQuery->loadPlugin('metadata', null, false),
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
<div id="qw-body" class="ui-widget-content">
<?php
if ($request['view'] && $this->elementExists($request['view'])) :
    require $this->getElement($request['view']);
else :
?>
<?php require $this->getElement('header') ?>
<table id="qw-main-table" cellpadding="0" cellspacing="0">
    <tr id="qw-main" class="ui-widget-content">
        <td id="qw-left" class="ui-helper-hidden">
            <div id="qw-left-content">
                <?php Qwin::hook('viewLeft') ?>
                <?php require $this->getElement('left') ?>
            </div>
        </td>
        <td id="qw-splitter-left" class="qw-splitter ui-state-default">
            <div class="qw-splitter-content"></div>
        </td>
        <td id="qw-center">
            <?php Qwin::hook('viewContentHeader') ?>
            <div id="qw-content">
                <?php require $this->getElement('content') ?>
            </div>
        </td>
        <td id="qw-splitter-right" class="qw-splitter ui-state-default">
            <div class="qw-splitter-content"></div>
        </td>
        <td id="qw-right" class="ui-helper-hidden">
            <div id="qw-right-content">
                <?php Qwin::hook('viewRight') ?>
                <?php require $this->getElement('right') ?>
            </div>
        </td>
    </tr>
</table>
<div id="qw-footer" class="ui-state-default">
    <div id="qw-footer-arrow" class="ui-icon ui-icon-arrowthickstop-1-n"></div>
    <div id="qw-footer-time"></div>
    <div id="qw-copyright" class="ui-widget">Executed in <?php echo $widget->call('app')->getEndTime() ?>(s). <?php echo qw_t('LBL_FOOTER_COPYRIGHT') ?></div>
</div>
</div>
<?php
endif;
?>
</body>
</html>