jQuery(function($){
    function getWidgetObject()
    {
        $('body').append('<div id="ui-temp-widget-tcontent" class="ui-widget-content ui-helper-hidden"></div>');
        return $('#ui-temp-widget-tcontent');
    }

    $widget = getWidgetObject();
    /**
     * 设置背景颜色,让背景看起来更协调
     * @todo 允许自定义背景
     */
    $('body').css('background-color', $widget.css('background-color'));
    /**
     * 为各浏览器设置阴影
     * @todo IE浏览器下,阴影会造成盒子位移
     */
    var $widgetShadowColor = $widget.css('border-top-color');
    $('#ui-main, #ui-top-floating-bar, #ui-header-shortcut').css({
        '-moz-box-shadow' : '0px 0px 10px ' + $widgetShadowColor,
        '-webkit-box-shadow' : '0px 0px 10px ' + $widgetShadowColor,
        'box-shadow' : '0px 0px 10px ' + $widgetShadowColor
        //'filter' : 'progid:DXImageTransform.Microsoft.Shadow(color=' + $widgetShadowColor + ', Direction=135, Strength=3)'
    });

    $('button:not(.ui-button-none), input:submit, input:reset, input:button, a.ui-anchor').button();
    $('td.ui-field-radio, td.ui-field-checkbox').buttonset();

    $('#ui-header-shortcut a').qui({
        click: true,
        focus: true
    });
    $('#ui-header-shortcut a:first').addClass('ui-corner-bl');
    $('#ui-header-shortcut a:last').addClass('ui-header-shortcut-last-anchor');

    //$('div.ui-message-operation a, div.ui-operation-field a, div.ui-operation-field button').qui();
    $('button.ui-button, a.ui-button').qui({
        click: true,
        focus: true
    });
    $('table.ui-form-table input:text, table.ui-table textarea').qui();

    $('a.ui-action-controller').button({icons: {primary: 'ui-icon-triangle-1-e'},text: false});

    // 点击右下按钮,回到顶部
    $('#ui-bottom-floating-botton').click(function(){
        $('html, body').animate({
            scrollTop: $("html").offset().top
        }, 500);
        return false;
    })

    // 页眉导航
    $('#ui-top-floating-bar ul li.ui-top-bar-list').hover(
        function(){
            $(this)
                .find('a:first')
                .removeClass('ui-corner-all')
                .addClass('ui-state-hover ui-corner-top');
            $(this)
                .find('ul')
                .show();
            $(this).find('li').hover(
                function(){
                    $(this).addClass('ui-state-active').css('border', 'none')
                },function(){
                    $(this).removeClass('ui-state-active');
            });
        },function(){
            $(this)
                .find('a:first')
                .removeClass('ui-state-hover ui-corner-top')
                .addClass('ui-corner-all');
            $(this)
                .find('ul')
                .hide();
        }
    );

    // 点击盒子右上角,显示或隐藏盒子内容
    $('a.ui-box-title-icon')
        .qui()
        .click(function(){
            // 切换按钮
            var icon_obj = $(this).find('span');
            if(icon_obj.hasClass('ui-icon-circle-triangle-n'))
            {
                icon_obj.removeClass("ui-icon-circle-triangle-n").addClass("ui-icon-circle-triangle-s");
            } else {
                icon_obj.removeClass("ui-icon-circle-triangle-s").addClass("ui-icon-circle-triangle-n");
            }
            // 显示/隐藏指定内容
            var name = $(this).attr('name');
            if('' != name)
            {
                $(name).slideToggle('fast');
            }
	});

    $('table.ui-table tr').not('.ui-table-header').qui();
    $('table.ui-table td.ui-state-default').qui();
    $('table.ui-table td a.ui-jqgrid-icon').qui();
});