<?php
/**
 * 语言设置
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
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field">
            <?php echo Qwin_Util_JQuery::button('submit', qw_lang('ACT_SUBMIT'), 'ui-icon-check') ?>
            <?php echo Qwin_Util_JQuery::link('javascript:history.go(-1);', qw_lang('ACT_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
            <input type="hidden" name="_submit" value="1" />
        </div>
        <div class="ui-space-10px"></div>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-operation-content">
            <a class="ui-anchor" href="<?php echo qw_u($module->toUrl(), 'language', array('lang' => 'zh-CN')) ?>"><?php echo qw_lang('LBL_LANG_ZHCN') ?></a>
            <a class="ui-anchor" href="<?php echo qw_u($module->toUrl(), 'language', array('lang' => 'en')) ?>"><?php echo qw_lang('LBL_LANG_EN') ?></a>
        </div>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-space-10px"></div>
        <div class="ui-operation-field">
            <?php echo Qwin_Util_JQuery::button('submit', qw_lang('ACT_SUBMIT'), 'ui-icon-check') ?>
            <?php echo Qwin_Util_JQuery::link('javascript:history.go(-1);', qw_lang('ACT_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
        </div>
    </div>
    </form>
</div>
