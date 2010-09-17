<?php
/**
 * 主题设置
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-23 00:34:08
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<script type="text/javascript">
jQuery(function($){
    $('div.ui-image-list ul li').qui();
});
</script>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <a href="<?php echo qw_url(array('module' => 'Style', 'controller' => 'Theme')) ?>"><?php echo qw_lang('LBL_THEME')?></a>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content ui-image-list">
        <div class="ui-theme-operation ui-operation-field">
            <?php echo qw_jquery_button('submit', qw_lang('LBL_ACTION_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jquery_link('javascript:history.go(-1);', qw_lang('LBL_ACTION_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
            <input type="hidden" name="_submit" value="1" />
        </div>
        <hr class="ui-line ui-widget-content" />
        <ul>
<?php
foreach($theme as $row){
    $url = qw_url($this->_set, array('style' => $row['path_name']));
?>
            <li class="ui-widget-content ui-corner-all">
                <a href="<?php echo $url?>">
                    <img alt="<?php echo $row['name']?>" src="<?php echo QWIN_RESOURCE_PATH?>/js/jquery/image/<?php echo $row['picture']?>" />
                </a>
                <p>
                    <a href="<?php echo qw_url(array('module' => 'Style', 'controller' => 'Theme', 'action' => 'Edit', 'id' => $row['id'])) ?>" title="<?php echo qw_lang('LBL_ACTION_EDIT')?>"><?php echo $row['name']?></a>
                   
                </p>
            </li>
<?php
}
?>
        </ul>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-theme-operation ui-operation-field">
            <?php echo qw_jquery_button('submit', qw_lang('LBL_ACTION_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jquery_link('javascript:history.go(-1);', qw_lang('LBL_ACTION_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
        </div>
    </div>
    </form>
</div>