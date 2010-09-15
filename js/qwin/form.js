jQuery(function($){
    $('div.ui-icon-common a').qui();

    $('fieldset > legend').click(function(){
        var id = $(this).parent().attr('id');
        var tableId = id.replace(/fieldset/, 'table')
        $('#' + tableId).toggle();
    });

    // 
    /*$('#post-form').validate({
		rules: jQueryValidateCode.rules,
        //messages: jQueryValidateCode.messages,
        //errorClass: 'ui-state-error',
        errorPlacement: function(error, element) {
            error.appendTo( element.parent());
        },
        success: function(label) {
            label.addClass('ui-icon ui-icon-check').html('check!');
		},
        submitHandler: function(form){
            form.submit();
        },
        highlight: function(input){
            $(input).addClass('ui-state-highlight');
        },
        unhighlight: function(input){
            $(input).removeClass('ui-state-highlight');
        }
	});*/

    // 操作区域不添加 focus 事件
    //$('#post-form div.ui-field-common').find('input, textarea').not('.ui-form-button').qui({focus: true});
    //$('#ui-field-operation input.ui-form-button').qui();
	


    /*

    // 表单浏览模式 tabs 和 fieldset
    var qw_form = $('#post-form'),
        form_tab;
    $('#ui-tabs-button').toggle(
        function(){
            var form_tab_nav = $('<ul id="qw-form-tab-nav"></ul>');
            // 最后一个是表单操作域,所以不包含进 tab
            qw_form.find('legend:not(:last)').each(function(){
                var $this = $(this);
                form_tab_nav.append('<li><a href="#' + $this.parent().attr('id') + '">' + $this.html() + '</a></li>');
            });
            form_tab = qw_form
                .prepend(form_tab_nav)
                .tabs({
                    idPrefix: 'ui-fieldset-',
                    panelTemplate: '<form></form>'
                })
            // 添加按钮
            $('#ui-field-operation').append('<input type="button" id="ui-tabs-button-next" class="ui-form-button ui-state-default ui-corner-all" value="Next" /><input type="button" id="ui-tabs-button-previous" class="ui-form-button ui-state-default ui-corner-all" value="Previous" />');
            $('#ui-tabs-button-next').click(function(){
                form_tab.tabs('select', form_tab.tabs('option' , 'selected') + 1);
            });
            $('#ui-tabs-button-previous').click(function(){
                form_tab.tabs('select', form_tab.tabs('option' , 'selected') - 1);
            });
        },function(){
            // 移除按钮
            $('#ui-tabs-button-next, #ui-tabs-button-previous, #qw-form-tab-nav').remove();
            form_tab.tabs('destroy');
    });*/
});