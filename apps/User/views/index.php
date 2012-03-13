<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, draggable, dialog, datepicker, form, qui, button, metadata, jqGrid'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
<script type="text/javascript">
jQuery(function($){
    var addUrl = '<?php echo $this->url('user', 'add') ?>';
    var editUrl = '<?php echo $this->url('user', 'edit', array('id' => '{0}')) ?>';
    var deleteUrl = '<?php echo $this->url('user', 'delete', array('id' => '{0}')) ?>';

    $('#user-grid').jqGrid({
        sortorder: 'id',
        datatype: 'json',
        width: '100%',
        height: 'auto',
        forceFit: true,
        autowidth: true,
        rownumbers: true,
        multiselect: true,
        viewrecords: true,
        pager: '#user-grid-pager',
        rowNum: 15,
        rowList: [15, 30, 50, 100, 500],
        colNames: [
            '编号', '分组', '用户名', '邮箱', '性别', '创建者', '创建时间', '修改者', '修改时间', '操作'
        ],
        colModel: [{
            name: 'id',
            hidden: true
        }, {
            name: 'group_id',
            formatter: function(value) {
                return value;
            }
        }, {
            name: 'username'
        }, {
            name: 'email'
        }, {
            name: 'sex',
            align: 'center',
            formatter: function(value) {
                switch (value) {
                    case '0':
                        return '男';

                    case '1':
                        return '女';

                    case '2':
                    default:
                        return '未知'
                }
            }
        }, {
            name: 'created_by',
            hidden: true
        }, {
            name: 'date_created',
            align: 'center',
            hidden: true
        }, {
            name: 'modified_by',
            hidden: true
        }, {
            name: 'date_modified',
            align: 'center',
            hidden: true
        }, {
            name: 'operation',
            align: 'center',
            formatter: function(cellvalue, options, rowObject) {
                return '<a class="user-edit" data-id="' + rowObject[0] + '" data-username="' + rowObject[2] + '" href="javascript:;">编辑</a>'
                    + ' | <a class="user-delete" data-id="' + rowObject[0] +'" href="javascript:;">删除</a>';
            }
        }]
    }).jqGrid('navGrid', '#user-grid-pager', {
        add : false,
        edit : false,
        del : false,
        search : false
    }).jqGrid('fullContainer');

    $('a.user-edit').live('click', function(){
        window.location.href = qwin.format(editUrl, $(this).data('id'));
    });

    $('a.user-delete').live('click', function(){
        if (confirm('确认删除?')) {
            $.ajax({
                url: qwin.format(deleteUrl, $(this).data('id')),
                success: function() {
                    $('#user-grid').trigger('reloadGrid');
                }
            });
        }
    });

    $('#user-add').click(function(){
        window.location.href = addUrl;
    });
});
</script>
<div class="qw-toolbar ui-state-default" id="qw-jqgrid-top">
    <a id="user-add" href="javascript:;"><?php echo $lang['Add'] ?></a>
</div>
<div class="qw-c"></div>
<table id="user-grid"></table>
<div id="user-grid-pager"></div>
</body>
</html>
