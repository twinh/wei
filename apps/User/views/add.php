<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, metadata, qui, button, ui.form, form, datepicker, selectmenu'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
    dirname(__FILE__) . '/form.js',
));
?>
</head>
<body>
<style type="text/css">
    html {
        overflow-y: scroll;
    }
    #user-form label {
        text-align: left;
    }
    #user-form {
        margin: 15px 10px 10px 10px;
    }
</style>
<script type="text/javascript">
jQuery(function($){
    // options for group_id
    userForm['fields'][0]['fields'][0]['sources'] = <?php echo $options ?>;

    $('#user-form').form(userForm).ajaxForm({
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
<form id="user-form" method="post" action="">
</form>
</body>
</html>