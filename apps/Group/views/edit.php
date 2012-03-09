<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, metadata, qui, button, ui.form, form'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
<style type="text/css">
    #group-form label {
        text-align: left;
    }
    #group-form {
        margin: 15px 10px 10px 10px;
    }
</style>
<script type="text/javascript">
jQuery(function($){
    $('#group-form').form({
        autoWidth: true,
        classes: '',
        data: <?php echo $data ?>,
        fields: [{
            label: '编号',
            name: 'id',
            type: 'plain'
        }, {
            label: '上级分组',
            name: 'parent_id',
            type: 'select',
            sources: <?php echo $options ?>
        }, {
            label: '名称',
            name: 'name'
        }, {
            label: '描述',
            name: 'description',
            type: 'textarea'
        }],
        buttons: [{
            label: '提交',
            type: 'submit'
        },{
            label: '重置',
            type: 'reset'
        }]
    }).ajaxForm({
        dataType: 'json',
        success: function(data){
            alert(data.message);
            if (0 == data.code) {
                parent.jQuery.dialog.close(window.frameElement.id);
            }
        }
    });
});
</script>
<form id="group-form" method="post" action="">
</form>
</body>
</html>