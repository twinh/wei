<?php
/**
 * mangementapplicationstructure
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
 * @since       2010-09-12 11:11:34
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
    	<?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content">
    	<div class="ui-space-10px"></div>
        <div class="ui-operation-field">
            <?php echo qw_jQuery_link(qw_url(array('module' => 'Management', 'controller' => 'ApplicationStructure', 'action' => 'Update')), qw_lang('ACT_UPDATE_APPLICATION_STRUCTURE'), 'ui-icon-refresh') ?>
            <!--<?php echo qw_jQuery_link(qw_url(array('module' => 'Management', 'controller' => 'Namespace')), qw_lang('ACT_NAMESPACE_LIST'), 'ui-icon-script') ?>
            <?php echo qw_jQuery_link(qw_url(array('module' => 'Management', 'controller' => 'Namespace', 'action' => 'Add')), qw_lang('ACT_ADD_NAMESPACE'), 'ui-icon-plus') ?>-->
            <?php echo qw_jQuery_link('javascript:history.go(-1);', qw_lang('ACT_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
        </div>
        <div class="ui-space-10px"></div>
        <hr class="ui-line ui-widget-content" />
    </div>
    </form>
</div>
