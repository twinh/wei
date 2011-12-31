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
    $('#ui-west-toggler-open').qui().click(function(){
        layout.close('west');
    });
    
    // 左栏菜单
    var menuHeader = null;
    $('#west-menu').accordion({
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
    $('#west-menu li').qui();
    $('#west-menu-title').qui();
    
    $('#qw-nav a').qui({
        click: true,
        focus: true
    });
});