<?php
/**
 * Style
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-5-23 0:34:08 utf-8 中文
 * @since     2010-5-23 0:34:08 utf-8 中文
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<script type="text/javascript">
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
    $('div.ui-theme-list ul li').qui();

    // 返回
    $('input.action-return').click(function(){
        history.go(-1);
    });
});
</script>
<style type="text/css">
.ui-theme-list ul{
    padding: 1em;
}
.ui-theme-list ul li{
    padding: 1em;
    margin: 1em;
    float: left;
    font-weight: bold;
}
.ui-form .ui-form-content .ui-theme-operation{
    float: left;
    clear: both;
    padding: 2em 0 2em 2em;
}
.ui-form .ui-form-content .ui-theme-operation .ui-field-common{
    width: auto;
}
.ui-line{
    clear: both;
    width: auto;
    margin: 0 2em;
}
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <a href="<?php echo url(array($this->__query['namespace'], 'Style', 'Theme'))?>"><?php echo $this->t('LBL_THEME')?></a>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content ui-theme-list">
        <div class="ui-widget-content ui-theme-operation ui-block-common">
            <div class="ui-field-common ui-field-operation">
                <form method="post" name="form" action="<?php echo url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], 'ApplyTheme'))?>">
                <input type="submit" class="ui-form-button ui-state-default ui-corner-all" value="<?php echo $this->t('LBL_ACTION_APPLY')?>" title="<?php echo $this->t('LBL_APPLY_THEME')?>" />
                <input type="button" class="action-return ui-form-button ui-state-default ui-corner-all" value="<?php echo $this->t('LBL_ACTION_RETURN')?>" />
                </form>
            </div>
        </div>
        <hr class="ui-line ui-widget-content" />
        <ul>
<?php
foreach($theme as $row){
$url = url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], $this->__query['action']), array('style' => $row['path_name']));
    ?>
            <li class="ui-widget-content ui-corner-all">
                <a href="<?php echo $url?>">
                    <img alt="<?php echo $row['name']?>" src="<?php echo RESOURCE_PATH?>/js/jquery/image/<?php echo $row['picture']?>" />
                </a>
                <p>
                    <a href="<?php echo url(array($this->__query['namespace'], 'Style', 'Theme', 'Edit'), array('id' => $row['id']))?>" title="<?php echo $this->t('LBL_ACTION_EDIT')?>"><?php echo $row['name']?></a>
                   
                </p>
            </li>
<?php
}
?>
        </ul>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-widget-content ui-theme-operation ui-block-common">
            <div class="ui-field-common ui-field-operation">
                <form method="post" name="form" action="<?php echo url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], 'ApplyTheme'))?>">
                <input type="submit" class="ui-form-button ui-state-default ui-corner-all" value="<?php echo $this->t('LBL_ACTION_APPLY')?>" title="<?php echo $this->t('LBL_APPLY_THEME')?>" />
                <input type="button" class="action-return ui-form-button ui-state-default ui-corner-all" value="<?php echo $this->t('LBL_ACTION_RETURN')?>" />
                </form>
            </div>
        </div>
    </div>
</div>
