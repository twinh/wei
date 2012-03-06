<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, metadata, qui, button, ui.form, datepicker, selectmenu'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
<script type="text/javascript">
jQuery(function($){
    $('#user-form').form({
        lableDefaults: {
            width: 65
        },
        items: [{
            name: 'group_id'
        }, {
            name: 'username'
        }, {
            name: 'email'
        }]
    });
});
</script>
<form id="user-form" method="post" action="">
</form>
</body>
</html>