<style type="text/css">
    #qw-form-login {
        min-width: 200px;
    }
    #qw-form-login td {
        padding: 1px 0;
    }
    #qw-form-login label {
        padding-right: 4px;
    }
</style>
<script type="text/javascript">
jQuery(function($){
    $('#qw-form-login input').qui();
    
    $('#qw-form-login').ajaxForm({
        dataType: 'json',
        success: function(data){
            alert(data.message);
            if (0 == data.code) {
                window.location.reload();
                //$('#login-dialog').dialog('destory').remove();
            }
        }
    });
});
</script>
<form id="qw-form-login" class="qw-form" action="?module=user&action=login" method="post">
    <table class="qw-form-table">
        <tr>
            <td width="20%"></td>
            <td width="80%"></td>
        </tr>
        <tr>
            <td class="qw-label-common qw-label-plain"><label for="point">用户名:</label></td>
            <td class="qw-field-common qw-field-text" colspan="1">
                <input id="login-username" name="username" class="ui-widget-content ui-corner-all" type="text" />
            </td>
        </tr>
        <tr>
            <td class="qw-label-common qw-label-plain"><label for="reason">密码:</label></td>
            <td class="qw-field-common qw-field-password" colspan="1">
                <input id="login-password" name="password" class="ui-widget-content ui-corner-all" type="password" />
            </td>
        </tr>
    </table>
</form>