<?php
/**
 * 表单页
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
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 18:47:32
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
  <div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
  	<div class="ui-box-header">
    	<?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field">
        <?php
            echo qw_jquery_link(qw_url($set, array('action' => 'Index')), qw_lang('LBL_ACTION_LIST'), 'ui-icon-note'),
                 qw_jquery_link(qw_url($set, array('action' => 'Add')), qw_lang('LBL_ACTION_ADD'), 'ui-icon-plus');
            if(isset($data[$primaryKey])):
                echo qw_jquery_link(qw_url($set, array('action' => 'Edit', $primaryKey => $data[$primaryKey])), qw_lang('LBL_ACTION_EDIT'), 'ui-icon-tag'),
                     qw_jquery_link(qw_url($set, array('action' => 'View', $primaryKey => $data[$primaryKey])), qw_lang('LBL_ACTION_VIEW'), 'ui-icon-lightbulb'),
                     qw_jquery_link(qw_url($set, array('action' => 'Add', $primaryKey => $data[$primaryKey])), qw_lang('LBL_ACTION_COPY'), 'ui-icon-transferthick-e-w'),
                     qw_jquery_link('javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . qw_url($set, array('action' => 'Delete', $primaryKey => $data[$primaryKey])) . '\'};', qw_lang('LBL_ACTION_DELETE'), 'ui-icon-trash');
            endif;
            echo qw_jquery_link('javascript:history.go(-1);', qw_lang('LBL_ACTION_RETURN'), 'ui-icon-arrowthickstop-1-w');
            ?>
        </div>
        <?php require $this->decodePath('<resource><theme>/<namespace>/element/basic/form<suffix>') ?>
        <div class="ui-operation-field">
            <?php
            echo qw_jquery_link(qw_url($set, array('action' => 'Index')), qw_lang('LBL_ACTION_LIST'), 'ui-icon-note'),
                 qw_jquery_link(qw_url($set, array('action' => 'Add')), qw_lang('LBL_ACTION_ADD'), 'ui-icon-plus');
            if(isset($data[$primaryKey])):
                echo qw_jquery_link(qw_url($set, array('action' => 'Edit', $primaryKey => $data[$primaryKey])), qw_lang('LBL_ACTION_EDIT'), 'ui-icon-tag'),
                     qw_jquery_link(qw_url($set, array('action' => 'View', $primaryKey => $data[$primaryKey])), qw_lang('LBL_ACTION_VIEW'), 'ui-icon-lightbulb'),
                     qw_jquery_link(qw_url($set, array('action' => 'Add', $primaryKey => $data[$primaryKey])), qw_lang('LBL_ACTION_COPY'), 'ui-icon-transferthick-e-w'),
                     qw_jquery_link('javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . qw_url($set, array('action' => 'Delete', $primaryKey => $data[$primaryKey])) . '\'};', qw_lang('LBL_ACTION_DELETE'), 'ui-icon-trash');
            endif;
            echo qw_jquery_link('javascript:history.go(-1);', qw_lang('LBL_ACTION_RETURN'), 'ui-icon-arrowthickstop-1-w');
            ?>
        </div>
    </div>
  </div>