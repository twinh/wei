<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, draggable, dialog, qui, button, metadata, jqGrid'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
<script type="text/javascript">
jQuery(function($){
    var aclUrl = '<?php echo $this->url('acl', 'assign', array('groupId' => '{0}')) ?>';

    $('#group-grid').jqGrid({
        sortorder: 'id',
        datatype: 'json',
        width: '100%',
        height: 'auto',
        forceFit: true,
        autowidth: true,
        viewrecords: true,
        pager: '#group-grid-pager',
        colNames: [
            '编号', '名称', '创建者', '修改者', '创建时间', '修改时间', '操作'
        ],
        colModel: [{
            name: 'id',
            hidden: true
        }, {
            name: 'name'
        }, {
            name: 'created_by'
        }, {
            name: 'updated_by'
        }, {
            name: 'created_at',
            align: 'center'
        }, {
            name: 'updated_at',
            align: 'center'
        }, {
            name: 'operation',
            align: 'center',
            formatter: function(cellvalue, options, rowObject) {
                return '<a class="group-addchild" data-id="' + rowObject[0] + '" href="javascript:;">添加子分组</a>'
                    + ' | <a href="' + qwin.formatUrl(aclUrl, rowObject[0]) + '">分配权限</a>'
                    + ' | <a class="group-edit" data-id="' + rowObject[0] + '" href="javascript:;">编辑</a>'
                    + ' | <a class="group-delete" data-id="' + rowObject[0] +'" href="javascript:;">删除</a>';
            }
        }],
        treeGrid: true,
        ExpandColumn : 'name',
        ExpandColClick: true
    }).jqGrid('navGrid', '#group-grid-pager', {
        add : false,
        edit : false,
        del : false,
        search : false
    }).jqGrid('fullContainer');

    $('a.group-edit').live('click', function(){
        $.dialog({
            url: '?module=group&amp;action=edit&amp;id=' + $(this).data('id'),
            title: '编辑分组',
            width: 400,
            height: 300,
            close: function(){
                $('#group-grid').trigger('reloadGrid');
                $(this).dialog('destroy').remove();
            }
        });
    });

    $('a.group-delete').live('click', function(){
        if (confirm('当前分组与子分组将被删除,确认删除?')) {
            $.ajax({
                url: '?module=group&action=delete&id=' + $(this).data('id'),
                success: function() {
                    $('#group-grid').trigger('reloadGrid');
                }
            });
        }
    });

    $('a.group-addchild').live('click', function(){
        groupAdd($(this).data('id'));
    });

    $('#group-add').click(groupAdd);

    function groupAdd(id) {
        var url = '?module=group&amp;action=add';
        if (id) {
            url += '&parentId=' + id;
        }
        $.dialog({
            url: url,
            title: '添加分组',
            width: 400,
            height: 265,
            close: function() {
                $('#group-grid').trigger('reloadGrid');
                $(this).dialog('destroy').remove();
            }
        });
    }
});
</script>
<div class="qw-toolbar ui-state-default" id="qw-jqgrid-top">
    <a id="group-add" href="javascript:;">添加</a>
</div>
<div class="qw-c"></div>
<table id="group-grid"></table>
<div id="group-grid-pager"></div>
</body>
</html>