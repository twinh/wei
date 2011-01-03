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
	 if('transparent' == $('body').css('background-color'))
	 {
		 $('body').css('background-color', $widget.css('background-color'));
	 }
	
    $('button:not(.ui-button-none), input:submit, input:reset, input:button, a.ui-anchor').button();
    $('td.ui-field-radio, td.ui-field-checkbox').buttonset();

    $('#ui-main-left ul li').qui();
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
    $('table.ui-form-table input:text, table.ui-form-table textarea').qui();

    $('a.ui-action-controller').button({icons: {primary: 'ui-icon-triangle-1-e'},text: false});

    // 点击右下按钮,回到顶部
    $('#ui-footer-arrow').click(function(){
        $('html').animate({scrollTop:0}, 700);
        return false;
    })

    // 页眉导航
    $('#ui-top-floating-bar ul li.ui-top-bar-list').hover(
        function(){
            var firstAnchor = $(this).find('a:first');
            var ul = $(this).find('ul');

            firstAnchor.addClass('ui-state-hover');
            if('' != $.trim(ul.html())){
                firstAnchor
                    .removeClass('ui-corner-all')
                    .addClass('ui-corner-top');
                ul.show();
            }
        },function(){
            var firstAnchor = $(this).find('a:first');
            var ul = $(this).find('ul');
            
            firstAnchor
                .removeClass('ui-state-hover ui-corner-top')
                .addClass('ui-corner-all');
            ul.hide();
        }
    )
    .find('li').hover(
        function(){
            $(this).addClass('ui-state-active').css('border', 'none')
        },function(){
            $(this).removeClass('ui-state-active');
    });

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
	
	// 修复中间栏不能达到最大高度的问题
	fixMainTableHeight();
	function fixMainTableHeight()
	{
		var height = $(window).height() - $('#ui-main-table').offset().top - $('#ui-floating-footer').height();
		$('#ui-main-table').css('height', height);
	}
});