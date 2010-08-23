<?php
/**
 * list 的名称
 *
 * list 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-10-31 01:19:12
 * @since     2009-11-24 18:47:32
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
    <a class="list_show_link" href="javascript:void(0)"><?php echo qw_lang('LBL_ACTION_SHOW') ?></a>&nbsp;|&nbsp;
    <a class="action-clone" href="javascript:void(0)"><?php echo qw_lang('LBL_ACTION_COPY') ?></a>&nbsp;|&nbsp;
    <!--<a class="action-filter" href="Add"><?php echo $lang->t('LBL_ACTION_FILTER')?></a>-->
</div>
<table id="ui-jqgrid-table"></table>
<div id="ui-jqgrid-page"></div>
<script type="text/javascript">
jQuery(function($){
    $.jgrid.no_legacy_api = true;
    $.jgrid.useJSON = true;
    var primary_key = '<?php echo $primaryKey?>';
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
        ondblClickRow    : function(row_id, row, col ,e)
        {
            var row_data = $("#ui-jqgrid-table").jqGrid('getRowData', row_id),
                addition = {};
            addition[primary_key] = row_data[primary_key];
            var url = Qwin.url.auto({
                0 : _get['namespace'],
                1 : _get['module'],
                2 : _get['controller'],
                3 : 'Show'
            }, addition);
            window.location.href = url;
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
        var row_arr = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(row_arr.length != 1)
        {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var row_data = $("#ui-jqgrid-table").jqGrid('getRowData', row_arr[0]),
            addition = {};
        addition[primary_key] = row_data[primary_key];
        var url = Qwin.url.auto({
            0 : _get['namespace'],
            1 : _get['module'],
            2 : _get['controller'],
            3 : 'Edit'
        }, addition);
        window.location.href = url;
        return false;
    });
    // 点击删除按钮
    $('#t_ui-jqgrid-table a.action-delete').click(function(){
        var key_arr = new Array(),
            row_arr = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(row_arr.length == 0)
        {
            alert(Qwin.Lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for(var i in row_arr)
        {
            var row_data = $("#ui-jqgrid-table").jqGrid('getRowData', row_arr[i]);
            key_arr[i] = row_data[primary_key];
        }
        var addition = {};
        addition[primary_key] = key_arr.join(',');
        if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE))
        {

            var url = Qwin.url.auto({
                0 : _get['namespace'],
                1 : _get['module'],
                2 : _get['controller'],
                3 : 'Delete'
            }, addition);
            window.location.href = url;
            return false;
        }
        return false;
    });
    // 点击复制按钮
    $('#t_ui-jqgrid-table a.action-clone').click(function(){
        var row_arr = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(row_arr.length != 1)
        {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var row_data = $("#ui-jqgrid-table").jqGrid('getRowData', row_arr[0]),
            addition = {};
        addition[primary_key] = row_data[primary_key];
        var url = Qwin.url.auto({
            0 : _get['namespace'],
            1 : _get['module'],
            2 : _get['controller'],
            3 : 'Add'
        }, addition);
        window.location.href = url;
        return false;
    });
    // 点击查看按钮
    $('#t_ui-jqgrid-table a.list_show_link').click(function(){
        var row_arr = $('#ui-jqgrid-table').jqGrid('getGridParam','selarrrow');
        if(row_arr.length != 1)
        {
            alert(Qwin.Lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var row_data = $("#ui-jqgrid-table").jqGrid('getRowData', row_arr[0]),
            addition = {};
        addition[primary_key] = row_data[primary_key];
        var url = Qwin.url.auto({
            0 : _get['namespace'],
            1 : _get['module'],
            2 : _get['controller'],
            3 : 'Show'
        }, addition);
        window.location.href = url;
        return false;
    });
});
</script>
