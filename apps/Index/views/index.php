<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title><?php echo $lang['Qwin Framework'] ?></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->getCore(),
    $jQuery->get('ui.core, effects, qui, ui.tabs, ui.accordion, ui.button, layout, metadata'),
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
));
?>
<style type="text/css">
    
</style>
<script type="text/javascript">
    qwin.lang = <?php echo json_encode($lang->toArray()) ?>;
</script>
</head>
<body>
<div class="ui-layout-north">
    <div id="qw-logo" class="ui-widget-content">
        <a href="?">
            <img src="<?php echo $this->getUrlFile('views/images/logo.png') ?>" alt="logo" />
        </a>
    </div>
    <div class="qw-nav" id="qw-nav">
        <a class="ui-state-default ui-corner-bl" href="#">欢迎您, 游客!</a>
        <a class="ui-state-default" href="#">管理</a>
        <a class="ui-state-default" href="#">登陆</a>
    </div>
</div>
<div class="ui-layout-west">
    <h3 id="qw-menu-title" class="ui-state-default">
        <div id="ui-west-toggler-open" class="ui-state-default">
            <span class="ui-icon ui-icon-carat-1-w"></span>
        </div>
        <a href="javascript:alert('敬请期待!');" id="qw-menu-oper" class="ui-state-default">
            <span class="ui-icon ui-icon-gear"></span>
        </a>
        操作菜单
    </h3>
    <div id="qw-menu">
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
    <div id="qw-tabs">
        <ul>
            <li><a href="#ui-tabs-0"><?php echo $title ?></a></li>
        </ul>
        <div id="ui-tabs-0">
            <iframe class="ui-tabs-iframe" src="<?php echo $page ?>"></iframe>
        </div>
    </div>
</div>
<div class="ui-layout-east">
    
</div>
<div class="ui-layout-south">
    <div id="qw-footer" class="ui-state-default">
        <div id="qw-copyright" class="ui-widget"><?php echo $lang['LBL_FOOTER_COPYRIGHT'] ?></div>
    </div>
</div>
</body>
</html>