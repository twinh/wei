<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title><?php echo $lang['LBL_HTML_TITLE'] ?></title>
<?php
echo $this->getPackerSign();
$minify->add(array(
    $jQuery->loadTheme($this->options['theme']),
    $this->getFile('views/style.css'),
    $this->getFile('views/icons/icon.css'),
    $jQuery->loadCore(false),
    $jQuery->loadUi('widget', false),
    $jQuery->loadUi('core', false),
    $jQuery->loadUi('mouse', false),
    $jQuery->loadUi('button', false),
    $jQuery->loadUi('position', false),
    $jQuery->loadUi('draggable', false),
    $jQuery->loadUi('accordion', false),
    $jQuery->loadEffect('core', false),
    $jQuery->loadEffect('slide', false),
    $jQuery->loadPlugin('qui', null, false),
    $jQuery->loadPlugin('layout', null, false),
    $jQuery->loadPlugin('blockUI', null, false),
    $jQuery->loadPlugin('metadata', null, false),
    $this->getFile('views/style.js'),
));
// 加载页眉导航的缓存 todo index controller
$menus = require $this->cache->options['dir'] . 'menu.php';
//$viewOnly = $this->request->get('view-only');
?>
<style type="text/css">
    #west-menu .ui-icon {
        float: left;
    }
    #west-menu li.ui-state-hover {
        border: 0;
        font-weight: normal;
        background-image: none;
    }
</style>
<script type="text/javascript">
    qwin.lang = <?php echo json_encode($lang->toArray()) ?>;
    jQuery(function($){
        $('#west-menu li').qui();
        //ui-accordion-header ui-helper-reset ui-state-default ui-state-active ui-corner-top
    });
</script>
</head>
<body>
<div class="ui-layout-north">
    <table id="qw-header" class="ui-widget" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td id="qw-header-left">
                <div id="qw-logo" class="ui-widget-content">
                    <a href="?">
                        <img src="<?php echo $this->getUrlFile('views/images/logo.png') ?>" alt="logo" />
                    </a>
                </div>
            </td>
            <td id="qw-header-right" colspan="2">
                <?php $this->trigger('headerRight') ?>
            </td>
        </tr>
    </table>
</div>
<div class="ui-layout-west">
    <h3 id="west-menu-title" class="ui-state-default">
        <div id="ui-west-toggler-open" class="ui-state-default">
            <span class="ui-icon ui-icon-carat-1-w"></span>
        </div>
        操作菜单
    </h3>
    <div id="west-menu">
    <?php
    foreach ($menus[0] as $menu) :
    ?>
        <div>
            <h3><a href="<?php echo $menu['url'] ?>"><?php echo $menu['title'] ?></a></h3>
            <div>
            <?php
            if (isset($menus[1][$menu['id']])) :
            ?>
                <ul>
                    <?php
                    foreach ($menus[1][$menu['id']] as $subMenu) :
                    ?>
                    <li>
                        <span class="ui-icon ui-icon-document"></span>
                        <a href="<?php echo $subMenu['url'] ?>"><?php echo $subMenu['title'] ?></a>
                    </li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            <?php
            endif;
            ?>
            </div>
        </div>
    <?php
    endforeach;
    ?>
    </div>
</div>
<div class="ui-layout-center">
    <?php require $this->getElement('content') ?>
</div>
<div class="ui-layout-east">
    
</div>
<div class="ui-layout-south">
    <div id="qw-footer" class="ui-state-default">
        <div id="qw-copyright" class="ui-widget">Executed in <?php echo $this->app->getEndTime() ?>(s). <?php echo $lang['LBL_FOOTER_COPYRIGHT'] ?></div>
    </div>
</div>
</body>
</html>