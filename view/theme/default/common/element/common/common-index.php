<?php
/**
 * 列表页
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
?>
<!-- qwin-packer-sign -->
<?php
if ($isPopup) :
?>
<style type="text/css">
#t_ui-jqgrid-table{
    display : none;
}
.ui-jqgrid{
    padding: 0;
}
</style>
<?php require $this->decodePath('<resource><theme>/<defaultNamespace>/element/basic/jqgird<suffix>') ?>
<?php
else :
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
	<div class="ui-box-header">
    	<?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field">
        <div class="ui-jqgrid-top">
            <?php echo $this->loadWidget('Common_Widget_ListTab', array('asc' => $jqGrid['asc'], 'url' => $jqGrid['option']['url'])) ?>
        </div>
        <div class="clear"></div>
        <?php $jqGridWidget->render($jqGrid) ?>
        </div>
    </div>
</div>
<?php
endif;
?>
<script type="text/javascript">
jQuery(function($){
    var primaryKey = '<?php echo $primaryKey?>';

    // 点击删除按钮
    $('#action-<?php echo $ascString ?>-delete').click(function(){
        var keyList = new Array(),
            rowList = $('#<?php echo $jqGrid['id'] ?>').jqGrid('getGridParam','selarrrow');
        if (rowList.length == 0) {
            alert(Qwin.Lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for (var i in rowList) {
            var rowData = $('#<?php echo $jqGrid['id'] ?>').jqGrid('getRowData', rowList[i]);
            keyList[i] = rowData[primaryKey];
        }
        var addition = {};
        addition['action'] = 'Delete';
        addition[primaryKey] = keyList.join(',');
        if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE))
        {
            window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        }
        return false;
    });

    // 点击复制按钮
    $('#action-<?php echo $ascString ?>-copy').click(function(){
        var rowList = $('#<?php echo $jqGrid['id'] ?>').jqGrid('getGridParam','selarrrow');
        if (rowList.length != 1) {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = $('#<?php echo $jqGrid['id'] ?>').jqGrid('getRowData', rowList[0]);
        var url = $(this).attr('href') + '&' + primaryKey + '=' + rowData[primaryKey];
        window.location.href = url;
        return false;
    });
});
</script>
