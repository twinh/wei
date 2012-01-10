jQuery(function($){
    var layout = $('body').layout({
        defaults: {
            paneClass: 'ui-widget-content',
            resizerClass: 'ui-state-default',
            onresize_end: function() {
                // TODO rewrite select
                $('#qw-tabs div.ui-tabs-panel:not(.ui-tabs-hide)').innerHeight(
                    $('#qw-tabs').innerHeight() - $('#qw-tabs ul').outerHeight()
                );
            }
        },
        north: {
            resizable: false,
            slidable: false,
            spacing_closed: 0,
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
    });
    layout.addCloseBtn('#ui-west-toggler-open', 'west');
    
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
    
    $.ajax({
        url: '?module=user&action=isLogin',
        dataType: 'json',
        success: function(data) {
            if (0 == data.code) {
                $('#nav-username').text(data.username);
                $('#logout').show();
                $('#login').hide();
            } else {
                $('#nav-username').text('Guest');
            }
        }
    });
    
    $('#login').click(function(){
        var url = '?module=user&action=login';
        $('<div id="login-dialog">loading...</div>').load(url).dialog({
            title: '您好,请登陆',
            height: 190,
            width: 330,
            modal: true,
            buttons: {
                '登陆': function(){
                    $('#qw-form-login').submit();
                },
                '取消': function(){
                    $(this).dialog('destory').remove();
                }
            },
            close: function(){
                $(this).dialog('destroy').remove();
            }
        });
    });
    
    $('#logout').click(function(){
        $.ajax({
           url: '?module=user&action=logout',
           dataType: 'json',
           success: function(data) {
               alert(data.message);
               if (0 == data.code) {
                   window.location.reload();
               }
           }
        });
    });
    
    // 全屏切换
    var fullscreen = false;
    $('#qw-fullscreen').hover(function(){
        $(this).removeClass('ui-priority-secondary');
    }, function(){
        $(this).addClass('ui-priority-secondary');
    }).qui().click(function(){
        if (!fullscreen) {
            fullscreen = true;
            $('span', this).removeClass('ui-icon-arrow-4-diag').addClass('ui-icon-arrow-4');
            $.each('west,north,south'.split(','), function(index, value){
                layout.state[value].isHidden = false;
                layout.hide(value);
            });
        } else {
            fullscreen = false;
            $('span', this).removeClass('ui-icon-arrow-4').addClass('ui-icon-arrow-4-diag');
            $.each('west,north,south'.split(','), function(index, value){
                layout.state[value].isHidden = true;
                layout.show(value);
            });
        }
    });
});