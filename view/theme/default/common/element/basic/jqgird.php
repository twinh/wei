<?php
/**
 * jqgird
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-18 15:12:06
 */

$jQueryFile['jqgrid'] = $jQuery->loadPlugin('jqgrid', null, false);
$cssPacker
    ->add($jQueryFile['jqgrid']['css']);
$jsPacker
    ->add(QWIN_RESOURCE_PATH . '/js/jQuery/plugins/jqgrid/i18n/grid.locale-en.js')
    ->add($jQueryFile['jqgrid']['js']);
?>

<div class="ui-jqgrid-top">
    <?php echo $this->loadWidget('Common_Widget_ListTab', array('asc' => $jqGrid['asc'], 'url' => $jqGrid['url'])) ?>
</div>
<div class="clear"></div>
<table id="<?php echo $jqGrid['objectString'] ?>"></table>
<div id="<?php echo $jqGrid['pagerString'] ?>"></div>
<script type="text/javascript">
jQuery(function($){
    var jqGrid = <?php echo $jqGridJson?>;
    jqGrid.ondblClickRow = function(){};
    var primaryKey = '<?php echo $primaryKey?>';
	Qwin.App.primaryKey = primaryKey;

    <?php if (!isset($isPopup)) : ?>
    jqGrid.ondblClickRow = function(rowId, iRow, iCol, e){
        var rowData = $(jqGrid.object).jqGrid('getRowData', rowId),
            addition = {};
        addition['action'] = 'View';
        addition[primaryKey] = rowData[primaryKey];
        window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        return false;
    }
    <?php else : ?>
    jqGrid.ondblClickRow = function(rowId, iRow, iCol, e){
        var rowData = $(jqGrid.object).jqGrid('getRowData', rowId);
        $('<?php echo $popup['valueInput'] ?>').val(rowData['<?php echo $popup['valueColumn'] ?>']);
        $('<?php echo $popup['viewInput'] ?>').val(rowData['<?php echo $popup['viewColumn'] ?>'] + '(' + Qwin.Lang['LBL_SELECTED'] + ', ' + Qwin.Lang['LBL_READONLY'] + ')');
        $('#ui-popup').dialog('close');
    }
    <?php endif; ?>

    $(jqGrid.object)
        .jqGrid(jqGrid)
        .jqGrid('navGrid', jqGrid.pager,{
            add : false,
            edit : false,
            del : false,
            search : false
        });

    if (document.getElementById('ui-box-tab')) {
        $(jqGrid.object).jqGrid('setGridWidth', $('#ui-box-tab').width() - 30);
    }

    // 点击删除按钮
    $('#action-<?php echo $jqGrid['ascString'] ?>-delete').click(function(){
        var keyList = new Array(),
            rowList = $(jqGrid.object).jqGrid('getGridParam','selarrrow');
        if (rowList.length == 0) {
            alert(Qwin.Lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for (var i in rowList) {
            var rowData = $(jqGrid.object).jqGrid('getRowData', rowList[i]);
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
    $('#action-<?php echo $jqGrid['ascString'] ?>-copy').click(function(){
        var rowList = $(jqGrid.object).jqGrid('getGridParam','selarrrow');
        if (rowList.length != 1) {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = $(jqGrid.object).jqGrid('getRowData', rowList[0]);
        var url = $(this).attr('href') + '&' + primaryKey + '=' + rowData[primaryKey];
        window.location.href = url;
        return false;
    });

    // 样式调整
    $('.ui-jqgrid').width($('.ui-jqgrid').width() - 2).addClass('ui-state-default');
    $('table.ui-jqgrid-htable tr.ui-jqgrid-labels th:last').css('border-right', 'none');
});
</script>
