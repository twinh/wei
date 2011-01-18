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

$jQueryFile['jqgrid'] = $jquery->loadPlugin('jqgrid', null, false);
$cssPacker
    ->add($jQueryFile['jqgrid']['css']);
$jsPacker
    ->add(QWIN_RESOURCE_PATH . '/js/jquery/plugins/jqgrid/i18n/grid.locale-en.js')
    ->add($jQueryFile['jqgrid']['js']);
?>
<script type="text/javascript">
jQuery(function($){
    var jqGrid = <?php echo $jqGridJson?>;
    jqGrid.ondblClickRow = function(){};
    var primaryKey = '<?php echo $primaryKey?>';
	Qwin.App.primaryKey = primaryKey;

    <?php if (!$isPopup) : ?>
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

	// 页眉工具栏
    $(jqGrid.toolbarObject).appendTo('#t_' + jqGrid.objectString).removeClass('ui-helper-hidden');
    $('#t_' + jqGrid.object + ' a').qui({
        click: true,
        focus: true
    });

    // 点击删除按钮
    $('#action-delete').click(function(){
        var keyList = new Array(),
            rowList = $(jqGrid.object).jqGrid('getGridParam','selarrrow');
        if(rowList.length == 0)
        {
            alert(Qwin.Lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for(var i in rowList)
        {
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
});
</script>