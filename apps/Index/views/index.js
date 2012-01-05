jQuery(function($){
    var layout = $('body').layout({
        defaults: {
            paneClass: 'ui-widget-content',
            resizerClass: 'ui-state-default'
        },
        north: {
            resizable: false,
            slidable: false,
            spacing_open: 0,
            togglerClass: null
        },
        south: {
            resizable: false,
            slidable: false,
            spacing_closed: 0,
            spacing_open: 0,
            togglerClass: null
        },
        west: {
            minSize: 150,
            maxSize: 200,
            spacing_closed: 23,
            togglerAlign_closed: 'top',
            togglerContent_closed: '<div id="ui-west-toggler-closed" class="ui-state-default"><span class="ui-icon ui-icon-carat-1-e"></span></div>',
            togglerLength_closed: 22
        },
        east: {
            initClosed: true,
            resizable: false,
            slidable: false,
            spacing_closed: 0
        }
    }).addCloseBtn('#ui-west-toggler-open', 'west');
    
    // 左栏打开按钮增加鼠标滑过效果
    $('#ui-west-toggler-closed').qui();
    
    // 左栏关闭按钮增加点击事件
    $('#ui-west-toggler-open').qui();
    
    // 左栏菜单
    var menuHeader = null;
    $('#qw-menu').accordion({
        autoHeight: false,
        collapsible: true,
        header: 'h3',
        icons: false,
        create: function(event, ui) {
            menuHeader = $(this).find('h3:eq(1)').addClass('ui-accordion-next-header');
        },
        changestart: function(event, ui) {
            menuHeader.removeClass('ui-accordion-next-header');
            menuHeader = ui.newHeader.parent().next().find('h3');
            menuHeader.addClass('ui-accordion-next-header');
        }
    });
    $('#qw-menu li, #qw-menu-oper').qui();
    
    // 中间选项卡
    var tabs = $('#qw-tabs').tabs({
        closable: true,
        show:function(event, ui){
            $(ui.panel).innerHeight($('#qw-tabs').innerHeight() - $('#qw-tabs ul').outerHeight());
        }
    });

    tabs.find('ul.ui-tabs-nav')
        .removeClass('ui-widget-header ui-helper-reset')
        .addClass('ui-state-default');
    
    // 点击左栏显示选项卡
    $('#qw-menu li a').click(function(){
        $('#qw-tabs').tabs('addIframe', $(this).attr('href'), $(this).text());
        return false;
    });
    
    // 顶部导航
    $('#qw-nav a').qui({
        click: true,
        focus: true
    });
});