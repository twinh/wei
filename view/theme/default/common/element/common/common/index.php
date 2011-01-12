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

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
$jQueryFile['jqgrid'] = $jquery->loadPlugin('jqgrid', null, false);
$cssPacker
    ->add($jQueryFile['jqgrid']['css']);
$jsPacker
    ->add(QWIN_RESOURCE_PATH . '/js/jquery/plugin/jqgrid/i18n/grid.locale-en.js')
    ->add($jQueryFile['jqgrid']['js']);
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
	<div class="ui-box-header">
    	<?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field">
        <div id="custom-jqgird-toolbar" class="ui-helper-hidden">
        	<div class="ui-jqgrid-top">
				<?php echo $this->loadWidget('Common_Widget_ListTab') ?>
			</div>
         </div>
    	<table id="ui-jqgrid-table"></table>
    	<div id="ui-jqgrid-page"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $.jgrid.no_legacy_api = true;
    $.jgrid.useJSON = true;
    var primaryKey = '<?php echo $primaryKey?>';
	
	Qwin.App = {};
	Qwin.App.primaryKey = primaryKey;
	
    $("#ui-jqgrid-table").jqGrid({
        url              : '<?php echo $jgrid['url'] ?>',
        datatype         : 'json',
        colNames         : <?php echo $jgrid['colNames'] ?>,
        colModel         : <?php echo $jgrid['colModel'] ?>,
        rowNum           : <?php echo $jgrid['rowNum']?>,
        rowList          : [5, 10, 20, 30, 40, 50, 100],
        sortname         : '<?php echo $jgrid['sortname']?>',
        sortorder        : '<?php echo $jgrid['sortorder']?>',
        // 标题
        //caption          : '<?php echo qw_lang($meta['page']['title']) ?>',
        // 各参数的对应关系
        prmNames         : {
            page    : '<?php echo $jgrid['page'] ?>',
            rows    : '<?php echo $jgrid['rows'] ?>',
            sort    : '<?php echo $jgrid['sort'] ?>',
            order   : '<?php echo $jgrid['order'] ?>',
            search  : '<?php echo $jgrid['search'] ?>',
            nd      : 'nd',
            npage   : null
        },
        // 显示列的数目
        rownumbers       : true,
        // 允许多选
        multiselect      : true,
        // 高度设置为100%,使表格不出现Y滚动条
        height           : '100%',
        autowidth        : true,
        // 分页栏
        pager            : '#ui-jqgrid-page',
        // 分页栏右下角显示记录数
        viewrecords      : true,
        // 列宽度改变改变时,不改变表格宽度,从而不出现滚动条
        forceFit         : true,
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
		// 工具栏,设置在顶部
        toolbar          : [true, 'top']
    // 只显示刷新按钮
    }).jqGrid('navGrid','#ui-jqgrid-page',{
        add : false,
        edit : false,
        del : false,
        search : false
    });
	
	// 页眉工具栏
    $('#custom-jqgird-toolbar').appendTo("#t_ui-jqgrid-table").removeClass('ui-helper-hidden');

    $('#t_ui-jqgrid-table a').qui({
        click: true,
        focus: true
    });

    // 点击删除按钮
    $('#action-delete').click(function(){
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
});
</script>
