jQuery(function($){
    $('div.ui-icon-common a').qui();

    $('#post-form > fieldset > legend').click(function(){
        var id = $(this).parent().attr('id');
        $('#' + id + ' > div:not(.ui-block-hidden)').toggle();
            //$('#' + id + ' > div:not(.ui-block-hidden)').slideToggle('fast');
    });

    // 操作区域不添加 focus 事件
    $('#post-form div.ui-field-common').find('input, textarea').not('.ui-form-button').qui({focus: true});
    $('#ui-field-operation input.ui-form-button').qui();

    // 返回
    $('.action-return').click(function(){
        history.go(-1);
    });

    $('.icon-info-common').each(function(){
        //icon-info-id
        var id = this.id.slice(10);
        if('' != $('#ui-tip-' + id + ' ul').html())
        {
            $('#ui-tip-' + id).tip({
                object: '#icon-info-' + id
            });
            $(this).click(function(){
                $('#ui-tip-' + id).tip('open');
            });
        }
    });

    $('.ui-form-tip .ui-form-tip-content ul li').qui();

    // 验证
    if('undefined' != typeof(validator_rule))
    {
        $.each($.validator.messages, function(rule, val){
            $.validator.messages[rule] = rule;
        });
        var validator = $("#post-form").validate({
            rules: validator_rule,
            showErrors: function(self, errorMap)
            {
                // 关闭所有提示
                // TODO 简化
                $('.icon-info-common').each(function(){
                    //icon-info-id
                    var id = this.id.slice(10);
                    if('' != $('#ui-tip-' + id + ' ul').html())
                    {
                        $('#icon-info-' + id).find('span:last').not('.ui-icon-info').removeClass().addClass('ui-icon ui-icon-circle-check');
                        $('#ui-tip-' + id).tip('close');
                    }
                });
                // 移除所有错误的信息
                // TODO 精确化?
                $('.ui-tip-list').removeClass('ui-state-error');
                for(var i in errorMap)
                {
                    var id = errorMap[i]['element'].id
                        error_id = $('#validator-' + id + '-' + errorMap[i]['message']);
                    // 显示错误信息
                    $('#ui-tip-' + id).tip('open');
                    // 错误信息高亮,显示"×",表示不通过
                    error_id.addClass('ui-state-error').find('span').removeClass().addClass('ui-icon ui-icon-circle-close');
                    // 通过信息(过滤属于提示信息的条目,即包含类ui-icon-info)打勾,显示'√',表示通过.
                    error_id.prevAll().find('span').not('.ui-icon-info').removeClass().addClass('ui-icon ui-icon-circle-check');
                    // 未验证信息,显示"-",表示拒绝
                    error_id.nextAll().find('span').removeClass().addClass('ui-icon ui-icon-circle-minus');
                }
            },
            submitHandler: function(form){
                form.submit();
            }
        });
    }

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
    });
});