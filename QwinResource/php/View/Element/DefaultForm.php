<?php
/**
 * form 的名称
 *
 * form 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-10-31 01:19:12
 * @since     2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
qw('-rsc')->load('jquery/plugin/tip');
qw('-rsc')->load('jquery/plugin/validate');
// TODO
!isset($validator_rule) && $validator_rule = '""';
?>
<script type="text/javascript">
var validator_rule = <?php echo $validator_rule?>;
<?php
if(isset($form_view_type) && $form_view_type = 'tab')
{
?>
jQuery(function($){
    $('#ui-tabs-button').click();
});
<?php
}
?>
jQuery(function($){
    $(window).resize(function(){
        qwinAdjustBox('#ui-form');
    });
    qwinAdjustBox('#ui-form');
    function qwinAdjustBox(id)
    {
        // TODO 在chrome浏览器下,第一次加载,两者宽度一致
        var width = $('#ui-main').width() - $('#ui-main-leftbar').width() - 10;
        $(id).width(width);
    }
});
</script>
<?php
//qw('-rsc')->load('js/other/form');
qw('-rsc')->load('jquery/ui/tabs');
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <a href="<?php echo url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller']))?>"><?php echo $this->__meta['page']['title']?></a>
            <a href="javascript:void(0)" id="ui-tabs-button">[<?php echo $this->t('LBL_SWITCH_DISPLAY_MODEL')?>]</a>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <form id="post-form" name="form" method="post" action="<?php echo url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], $this->__query['action']))?>">
<?php
$i = 1;
foreach($set['field'] as $group_name => $group)
{
    if($group_name == '_custom')
    {
        continue;
    } elseif($group_name == ''){
        $group_name = $this->t('LBL_DEFAULT');
    }
?>
            <fieldset id="ui-fieldset-<?php echo $i?>" class="ui-widget-content ui-corner-all">
                <legend><?php echo $group_name?></legend>
                &nbsp;
<?php
    foreach($group as $field)
    {
        // 增加 jquery ui class
        qw('-str')->set($field['form']['class']);
        $field['form']['class'] .= ' ui-widget-content ui-corner-all';
?>
                <div class="ui-block-common ui-block-<?php echo $field['form']['_type']?>" id="ui-block-<?php echo $field['form']['name']?>">
                    <div class="ui-widget-content ui-label-common ui-label-<?php echo $field['form']['_type']?>" id="ui-label-<?php echo $field['form']['id']?>">
                        <label for="<?php echo $field['form']['id']?>"><?php echo $field['basic']['title']?>:</label>
                    </div>
                    <div class="ui-field-common ui-field-<?php echo $field['form']['_type']?>" id="ui-field-<?php echo $field['form']['id']?>">
                        <?php echo qwForm($field['form'], $data)?>
                    </div>
                    <div class="ui-icon-common ui-widget ui-helper-clearfix ui-state-highlight">
<?php
        // 显示提示信息的按钮
        if(isset($tip_data[$field['form']['name']]))
        {
?>
                        <a class="ui-state-default ui-corner-all icon-info-common" id="icon-info-<?php echo $field['form']['id']?>" href="javascript:void(0)">
                            <span class="ui-icon ui-icon-info"></span>
                        </a>
<?php
        }
?>
                        <?php echo qw('-btn')->auto($field['form'], $data)?>
                    </div>
                    <div class="ui-tip-common ui-tip-<?php echo $field['form']['id']?>" id="ui-tip-<?php echo $field['form']['id']?>">
                        <ul>
<?php
        if(isset($tip_data[$field['form']['name']]))
        {
            foreach($tip_data[$field['form']['name']] as $tip)
            {
?>
                            <li class="ui-tip-list" id="<?php echo $tip['id']?>"><span class="ui-icon <?php echo $tip['icon']?>"></span><?php echo $tip['data']?></li>
<?php
            }
        }
?>
                        </ul>
                    </div>
                </div>
<?php
    }
?>
            </fieldset>
<?php
    $i++;
}
?>
            <fieldset id="ui-fieldset-<?php echo $i?>" class="ui-widget-content ui-corner-all">
                <legend><?php echo $this->t('LBL_OPERATION')?></legend>
                &nbsp;
                    <div class="ui-block-common">
                        <div class="ui-label-common ui-widget-content">&nbsp;</div>
                        <div class="ui-field-common ui-field-operation" id="ui-field-operation">
                            <input type="hidden" name="_action" value="<?php echo $this->__query['action']?>" />
                            <input type="hidden" name="_page" value="<?php echo $http_referer?>" />
                            <input type="submit" class="ui-form-button ui-state-default ui-corner-all" id="submit" value="<?php echo $this->t('LBL_ACTION_SUBMIT')?>" />
                            <input type="reset" class="ui-form-button ui-state-default ui-corner-all" value="<?php echo $this->t('LBL_ACTION_RESET')?>" />
                            <input type="button" class="action-return ui-form-button ui-state-default ui-corner-all" value="<?php echo $this->t('LBL_ACTION_RETURN')?>" />
                        </div>
                        <div class="ui-icon-common ui-widget ui-helper-clearfix ui-state-default"> </div>
                    </div>
            </fieldset>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH?>/js/other/form.js"></script>
