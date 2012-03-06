<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title>您好,请登陆</title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, qui, button, form, ui.form, validate'),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
<style type="text/css">
    #login-form {
        margin: 15px 10px 10px 10px;
    }
</style>
<script type="text/javascript">
jQuery(function($){
    var loginForm = $('#login-form');
    loginForm.form({
        autoWidth: true,
        classes: '',
        labelDefaults: {
            width: 60
        },
        fields: [{
            name: 'username',
            label: '<?php echo $lang['Username'] ?>'
        }, {
            name: 'password',
            type: 'password',
            label: '<?php echo $lang['Password'] ?>'
        }],
        buttons: [{
            label: '登陆',
            type: 'submit'
        }, {
            label: '取消',
            click: function() {
                parent.jQuery.dialog.close(window.frameElement.id);
            }
        }]
    });
    /*loginForm.validate({
        rules: {
            username: {
                required: true,
                minlength: 2
            },
            password: {
                required: true,
                minlength: 5
            }
        },
        highlight: function(input) {
            $(input).addClass('ui-state-highlight');
        },
        unhighlight: function(input) {
            $(input).removeClass('ui-state-highlight');
        }
    });*/
    loginForm.ajaxForm({
        dataType: 'json',
        success: function(data){
            alert(data.message);
            if (0 == data.code) {
                parent.location.reload();
                //$('#login-dialog').dialog('destory').remove();
            }
        }
    });
});
</script>
<form id="login-form" action="<?php echo $this->url('user', 'login') ?>" method="post">
</form>
</body>
</html>