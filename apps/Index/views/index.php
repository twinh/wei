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
    $jQuery->loadUi('tabs', false),
    $jQuery->loadUi('accordion', false),
    $jQuery->loadUi('resizable', false),
    $jQuery->loadEffect('core', false),
    $jQuery->loadEffect('slide', false),
    $jQuery->loadPlugin('qui', null, false),
    $jQuery->loadPlugin('layout', null, false),
    $jQuery->loadPlugin('blockUI', null, false),
    $jQuery->loadPlugin('metadata', null, false),
    $jQuery->loadPlugin('tmpl', null, false),
    $this->getFile('views/style.js'),
));
// 加载页眉导航的缓存 todo index controller
$menus = require $this->cache->options['dir'] . 'menu.php';
//$viewOnly = $this->request->get('view-only');
?>
</head>
<body>
<div class="ui-layout-north">
    <div id="qw-logo" class="ui-widget-content">
        <a href="?">
            <img src="<?php echo $this->getUrlFile('views/images/logo.png') ?>" alt="logo" />
        </a>
    </div>
    <div class="qw-nav" id="qw-nav">
        <a class="ui-state-default ui-corner-bl" href="?member/my">欢迎您, 游客!</a>
        <!--<a class="ui-state-default" href="#">管理</a>-->
        <a class="ui-state-default qw-last-link qw-tabs-false" href="#">注销</a>
    </div>
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
<style type="text/css">
    
</style>
<script type="text/javascript">
    qwin.lang = <?php echo json_encode($lang->toArray()) ?>;
    jQuery(function($){
        tab_counter = 2;
        
        $tabs = $('#qw-tabs').tabs({
            closable: true,
            add: function( event, ui ) {
                var tab_content = "Tab " + tab_counter + " content.";
                $( ui.panel ).append( "<p>" + tab_content + "</p>" );
            }
        });
        
        $tabs.find('ul.ui-tabs-nav')
            .removeClass('ui-widget-header ui-helper-reset')
            .addClass('ui-state-default');
        
        // actual addTab function: adds new tab using the title input from the form above
        function addTab(title) {
            var id = '#tabs-' + tab_counter;
            $tabs.tabs("add", id, title);
            tab_counter++;
        }
        
        addTab('用户管理');
        addTab('用户管理2');
        addTab('用户管理3');
        addTab('用户管理3');
        addTab('用户管理3');
        addTab('用户管理3');
        addTab('用户管理3');
    });
</script>
<div class="ui-layout-center">
    <div id="qw-tabs">
        <ul>
            <li><a href="#tabs-1">欢迎光临</a></li>
        </ul>
        <div id="tabs-1">
            <?php require 'index-content.php' ?>
        </div>
    </div>
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