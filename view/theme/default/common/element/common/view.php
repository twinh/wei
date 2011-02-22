<?php
/**
 * 显示一条记录的视图
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
defined('QWIN') or exit('Forbidden');
$jQueryFile['tabs'] = $jQuery->loadUi('tabs', false);
$minify->add($jQueryFile['tabs']['css'])
        ->add($jQueryFile['tabs']['js']);
$operationField =  $this->loadWidget('Common_Widget_FormLink', array($data, $primaryKey));
?>
<script type="text/javascript">
jQuery(function($) {
    $('fieldset > legend').click(function(){
        $(this).next().toggle();
    });

    $('table.ui-form-table input, table.ui-form-table select').addClass('ui-widget-content ui-corner-all');
    $('#ui-box-tab').tabs();
});
</script>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
<div class="ui-box-header">
    <?php $this->loadWidget('Common_Widget_Header') ?>
</div>
<div class="ui-form-content ui-box-content ui-widget-content">
    <div class="ui-operation-field">
        <?php echo $operationField ?>
    </div>
    <div id="ui-box-tab" class="ui-box-tab">
        <ul>
            <li><a href="#ui-tab-1"><?php echo $lang[$meta['page']['title']] . $lang['LBL_TAB_DATA'] ?></a></li>
        <?php
        foreach($tabTitle as $alias => $title):
        ?>
            <li><a href="#ui-tab-<?php echo $alias?>"><?php echo $title ?></a></li>
        <?php
        endforeach;
        ?>
        </ul>
    <div id="ui-tab-1">
    <?php $formWidget->render($formOption); ?>
    </div>
    <?php
    foreach($jqGridList as $alias => $jqGrid):
    ?>
    <div id="ui-tab-<?php echo $alias ?>">
        <?php require $this->decodePath('<resource><theme>/<defaultNamespace>/element/basic/jqgird<suffix>') ?>
    </div>
    <?php
    endforeach;
    ?>
    </div>
</div>
</div>
