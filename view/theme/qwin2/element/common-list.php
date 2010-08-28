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
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH ?>/js/jquery/plugin/jqgrid/i18n/grid.locale-en.js"></script>
<?php echo $jquery->loadPlugin('jqgrid') ?>
<div id="custom-jqgird-toolbar" class="ui-helper-hidden">
    &nbsp;<a class="action-add" href="<?php echo qw_url($set, array('action' => 'Add')) ?>"><?php echo $lang->t('LBL_ACTION_ADD')?></a>&nbsp;|&nbsp;
    <a class="action-edit" href="javascript:void(0)"><?php echo qw_lang('LBL_ACTION_EDIT') ?></a>&nbsp;|&nbsp;
    <a class="action-delete" href="javascript:void(0)"><?php echo qw_lang('LBL_ACTION_DELETE') ?></a>&nbsp;|&nbsp;
    <a class="list_show_link" href="javascript:void(0)"><?php echo qw_lang('LBL_ACTION_VIEW') ?></a>&nbsp;|&nbsp;
    <a class="action-clone" href="javascript:void(0)"><?php echo qw_lang('LBL_ACTION_COPY') ?></a>&nbsp;|&nbsp;
    <!--<a class="action-filter" href="Add"><?php echo $lang->t('LBL_ACTION_FILTER')?></a>-->
</div>
<table id="ui-jqgrid-table"></table>
<div id="ui-jqgrid-page"></div>
<script type="text/javascript">
jQuery(function($){
    $.jgrid.no_legacy_api = true;
    $.jgrid.useJSON = true;
    var primaryKey = '<?php echo $primaryKey?>';
    $("#ui-jqgrid-table").jqGrid({
        url              : '<?php echo $jsonUrl?>',
        datatype         : 'json',
        colNames         : <?php echo $columnName?>,
        colModel         : <?php echo $columnSetting?>,
        rowNum           : <?php echo $rowNum?>,
        rowList          : [5, 10, 20, 30, 40, 50, 100],
        sortname         : '<?php echo $sortName?>',
        sortorder        : '<?php echo $sortOrder?>',
        // 标题
        caption          : '<a href="<?php echo qw_url() ?>"><?php echo qw_lang($meta['page']['title']) ?></a>',
        // 显示列的数目
        rownumbers       : true,
        // 允许多选
        multiselect      : true,
        // 高度设置为100%,使表格不出现Y滚动条
        height           : '100%',
		// TODO 宽度自适应
        width            : $('#ui-main').width() - 6,
        // 分页栏
        pager            : '#ui-jqgrid-page',
        // 分页栏右下角显示记录数
        viewrecords      : true,
        // 列宽度改变改变时,不改变表格宽度,从而不出现滚动条
        forceFit         : true,
        // 工具栏,设置在顶部
        toolbar          : [true, 'top'],
        // 双击查看详情
        ondblClickRow    : function(rowId, row, col ,e)
        {
            var rowData = $("#ui-jqgrid-table").jqGrid('getRowData', rowId),
                addition = {};
            addition['action'] = 'View';
            addition[primaryKey] = rowData[primaryKey];
            window.location.href = Qwin.url.createUrl(Qwin.get, addition);
            return false;
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
    // 页眉工具栏
    $("#t_ui-jqgrid-table").append($('#custom-jqgird-toolbar').html());

    // 点击编辑按钮
    $('#t_ui-jqgrid-table a.action-edit').click(function(){
        var rowList = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(rowList.length != 1)
        {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = $("#ui-jqgrid-table").jqGrid('getRowData', rowList[0]),
            addition = {};
        addition['action'] = 'Edit'
        addition[primaryKey] = rowData[primaryKey];
        window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        return false;
    });
    // 点击删除按钮
    // TODO 提示更多信息,包括id号,数目等
    $('#t_ui-jqgrid-table a.action-delete').click(function(){
        var keyList = new Array(),
            rowList = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(rowList.length == 0)
        {
            alert(Qwin.Lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for(var i in rowList)
        {
            var rowData = $("#ui-jqgrid-table").jqGrid('getRowData', rowList[i]);
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
    $('#t_ui-jqgrid-table a.action-clone').click(function(){
        var rowList = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(rowList.length != 1)
        {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = $("#ui-jqgrid-table").jqGrid('getRowData', rowList[0]),
            addition = {};
        addition['action'] = 'Add';
        addition[primaryKey] = rowData[primaryKey];
        window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        return false;
    });
    // 点击查看按钮
    $('#t_ui-jqgrid-table a.list_show_link').click(function(){
        var rowList = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(rowList.length != 1)
        {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = $("#ui-jqgrid-table").jqGrid('getRowData', rowList[0]),
            addition = {};
        addition['action'] = 'View';
        addition[primaryKey] = rowData[primaryKey];
        window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        return false;
    });
});
</script>
