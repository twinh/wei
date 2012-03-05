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
            '编号', '分组', '用户名', '邮箱', '性别', '修改时间', '操作'
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
                return 1 == value ? '男' : '女';
            }
        }, {
            name: 'date_modified',
            align: 'center'
        }, {
            name: 'operation',
            align: 'center',
            formatter: function(cellvalue, options, rowObject) {
                return '<a href="?module=user&action=edit&id=' + rowObject[0] + '">编辑</a> | <a href="#">删除</a>';
            }
        }]
    }).jqGrid('navGrid', '#user-grid-pager', {
        add : false,
        edit : false,
        del : false,
        search : false
    }).jqGrid('fullContainer');
});
</script>
<table id="user-grid"></table>
<div id="user-grid-pager"></div>
</body>
</html>
