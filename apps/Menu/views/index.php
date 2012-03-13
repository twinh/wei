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
    var editUrl = '<?php echo $this->url('menu', 'edit', array('id' => '{0}')) ?>';
    var addUrl = '<?php echo $this->url('menu', 'add') ?>';
    var deleteUrl = '<?php echo $this->url('menu', 'delete', array('id' => '{0}')) ?>';

    $('#menu-grid').jqGrid({
        datatype: 'json',
        width: '100%',
        height: 'auto',
        forceFit: true,
        autowidth: true,
        viewrecords: true,
        pager: '#menu-grid-pager',
        colNames: [
            '编号', '分组', '名称', '链接', '目标', '顺序', '操作'
        ],
        colModel: [{
            name: 'id',
            hidden: true
        }, {
            name: 'catrgory_id',
            hidden: true
        }, {
            name: 'title'
        }, {
            name: 'url'
        }, {
            name: 'target',
            align: 'center'
        }, {
            name: 'order',
            align: 'center'
        }, {
            name: 'operation',
            align: 'center',
            formatter: function(cellvalue, options, rowObject) {
                return '<a class="menu-edit" data-id="' + rowObject[0] + '" data-username="' + rowObject[2] + '" href="javascript:;">编辑</a>'
                    + ' | <a class="menu-delete" data-id="' + rowObject[0] +'" href="javascript:;">删除</a>';
            }
        }],
        treeGrid: true,
        treeGridModel: 'adjacency',
        ExpandColumn : 'title'
    }).jqGrid('navGrid', '#menu-grid-pager', {
        add : false,
        edit : false,
        del : false,
        search : false
    }).jqGrid('fullContainer');

    $('a.menu-edit').live('click', function(){
        $.dialog({
            url: qwin.formatUrl(editUrl, $(this).data('id')),
            title: '编辑菜单',
            width: 400,
            height: 270,
            beforeClose: function() {
                $('#menu-grid').trigger('reloadGrid');
            }
        });
    });

    $('a.menu-delete').live('click', function(){
        if (confirm('确认删除?')) {
            $.ajax({
                url: qwin.formatUrl(deleteUrl, $(this).data('id')),
                success: function() {
                    $('#user-grid').trigger('reloadGrid');
                }
            });
        }
    });

    $('#menu-add').click(function(){
        $.dialog({
            url: addUrl,
            title: '添加菜单',
            width: 400,
            height: 270,
            beforeClose: function() {
                $('#menu-grid').trigger('reloadGrid');
            }
        });
    });
});
</script>
<div class="qw-toolbar ui-state-default" id="qw-jqgrid-top">
    <a id="menu-add" href="javascript:;"><?php echo $lang['Add'] ?></a>
</div>
<div class="qw-c"></div>
<table id="menu-grid"></table>
<div id="menu-grid-pager"></div>
</body>
</html>