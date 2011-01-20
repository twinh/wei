jQuery(function($){
    $('td.ui-mapeditor-oper a').qui();

    $('.ui-mapeditor .ui-mapeditor-oper span.ui-icon-circle-close').live('click', function(){
        deleteItem($(this));
    });

    $('.ui-mapeditor-button').click(function(){
        var number = parseInt($('#ui-mapeditor-number').val());
        if (0 >= number ) {
            number = 1;
        }
        for (var i = 0; i < number; i++) {
            addItem();
        }
    });

    // 删除一项
    function deleteItem(object)
    {
        var liList = $('.ui-mapeditor li');
        // 如果是最后一个,则清空数据,不删除
        if (1 == liList.length) {
            clearItemForm(liList[0]);
        } else {
            object.parents('li').slideUp('normal', function(){
                $(this).remove();
            })
        }
    }

    // 清空表单数据
    function clearItemForm(element)
    {
        element = $(element);
        element.find('input').val('');
        element.find('option:first').attr('selected','selected');
        return element;
    }

    // 添加一项
    function addItem()
    {
        clearItemForm($('.ui-mapeditor li:first').clone())
            .hide()
            .appendTo($('#ui-mapeditor-sortable'))
            .slideDown()
            .find('input:first').val($('.ui-mapeditor li').length);
    }

    $('#<?php echo $meta['id'] ?>').hide();
    $( "#ui-mapeditor-sortable" ).sortable({
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,
        opacity: 0.9,
        scroll: false,
        create: function () {
            $(this).find('input')
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                    e.stopImmediatePropagation();
            });
        },
        stop: function () {
            $(this).find('input')
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                    e.stopImmediatePropagation();
            });
        }
    }).disableSelection();

    // 防止表单提交,转变为添加选项
    $('#ui-mapeditor-number').keypress(function(e){
        if(e.which == 13){
            $('.ui-mapeditor-button').click();
            return false;
        }
    });
});