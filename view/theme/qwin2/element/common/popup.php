<?php
/**
 * popup
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
 * @package     Qwin
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-03 19:22:14
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
$jquery = Qwin::run('-jquery');
?>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH ?>/js/jquery/plugin/jqgrid/i18n/grid.locale-en.js"></script>
<?php echo $jquery->loadPlugin('jqgrid') ?>
<div id="ui-popup-main">
    <table id="ui-jqgrid-table"></table>
    <div id="ui-jqgrid-page"></div>
</div>
<script type="text/javascript">
jQuery(function($){
    $.jgrid.no_legacy_api = true;
    $.jgrid.useJSON = true;
    var primaryKey = '<?php echo $primaryKey?>';
    var jqgridObj = $("#ui-jqgrid-table");
    jqgridObj.jqGrid({
        url              : 'manager.php<?php echo $jsonUrl?>',
        datatype         : 'json',
        colNames         : <?php echo $columnName?>,
        colModel         : <?php echo $columnSetting?>,
        rowNum           : <?php echo $rowNum?>,
        rowList          : [5, 10, 20, 30, 40, 50, 100],
        sortname         : '<?php echo $sortName?>',
        sortorder        : '<?php echo $sortOrder?>',
        // 标题
        caption          : null,
        // 显示列的数目
        rownumbers       : true,
        // 允许多选
        //multiselect      : true,
        // 高度设置为100%,使表格不出现Y滚动条
        height           : '100%',
		// TODO 宽度自适应
        width            : 600,
        // 分页栏
        pager            : '#ui-jqgrid-page',
        // 分页栏右下角显示记录数
        viewrecords      : true,
        // 列宽度改变改变时,不改变表格宽度,从而不出现滚动条
        forceFit         : true,

        ondblClickRow    : function(rowId, row, col ,e)
        {
            var rowData = jqgridObj.jqGrid('getRowData', rowId);
            $($.popupOpts.valueInput).val(rowData[$.popupOpts.valueColumn]);
            $($.popupOpts.viewInput).val(rowData[$.popupOpts.viewColumn] + '(' + Qwin.Lang['LBL_SELECTED'] + ', ' + Qwin.Lang['LBL_READONLY'] + ')');
            $.popupOpts.obj.dialog('close');
        },
        // 各参数的对应关系
        prmNames         : {
            page   : 'page',
            rows   : 'row',
            sort   : 'orderField',
            order  : 'orderType',
            search : '_search',
            nd     : 'nd',
            npage  : null
        }
    // 只显示刷新按钮
    }).jqGrid('navGrid','#ui-jqgrid-page',{
        add : false,
        edit : false,
        del : false,
        search : false
    });

    $('#t_ui-jqgrid-table a').qui({
        click: true,
        focus: true
    });
});
</script>
