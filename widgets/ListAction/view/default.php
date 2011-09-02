<?php
/**
 * 列表页
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
echo $this->getPackerSign();
?>
<div class="ui-jqgrid-top">
<?php Qwin::hook('viewListTop', array('view' => $this)) ?>
</div>
<div class="qw-c"></div>
<?php $jqGridWidget->render($jqGridOptions) ?>
<script type="text/javascript">
jQuery(function($){
    var primaryKey = '<?php echo $id?>';
    var jqGridObj = qwin.jqGrid = $('#<?php echo $jqGridWidget->getId() ?>');

    jqGridObj.jqGrid('setGridParam',{
        ondblClickRow: function(rowId, iRow, iCol, e){
            var rowData = jqGridObj.jqGrid('getRowData', rowId),
                addition = {};
            addition['action'] = 'view';
            addition[primaryKey] = rowData[primaryKey];
            window.location.href = qwin.url.createUrl(qwin.get, addition);
            return false;
    }});

    if (document.getElementById('ui-box-tab')) {
        jqGridObj.jqGrid('setGridWidth', $('#ui-box-tab').width() - 30);
    }

    // 点击删除按钮
    $('#action-<?php echo $module->getId() ?>-delete').click(function(){
        var keyList = new Array(),
            rowList = jqGridObj.jqGrid('getGridParam','selarrrow');
        if (rowList.length == 0) {
            alert(qwin.lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for (var i in rowList) {
            var rowData = jqGridObj.jqGrid('getRowData', rowList[i]);
            keyList[i] = rowData[primaryKey];
        }
        var addition = {};
        addition['action'] = 'delete';
        addition[primaryKey] = keyList.join(',');

        var message = '';
        if ($(this).find('span').hasClass('ui-icon-trash')) {
            message = qwin.lang.MSG_CONFIRM_TO_DELETE_TO_TRASH;
        } else {
            message = qwin.lang.MSG_CONFIRM_TO_DELETE;
        }
        if (confirm(message)) {
            window.location.href = qwin.url.createUrl(qwin.get, addition);
        }
        return false;
    });

    // 点击复制按钮
    $('#action-<?php echo $module->getId() ?>-copy').click(function(){
        var rowList = jqGridObj.jqGrid('getGridParam','selarrrow');
        if (rowList.length != 1) {
            alert(qwin.lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = jqGridObj.jqGrid('getRowData', rowList[0]);
        var url = $(this).attr('href') + '&' + primaryKey + '=' + rowData[primaryKey];
        window.location.href = url;
        return false;
    });

    // 根据窗口变化设置jqGrid宽度等
    // 修复360极速浏览器(6.0Chrome内核)宽度不正确的问题
    $(window).load(function() {
        setJqGridWidth();
    }).resize(function(){
        setJqGridWidth();
    });
    qwin.page.splitter.bind('toggle', function(){
        setJqGridWidth();
    });
    function setJqGridWidth() {
        // 窗口宽度 - 左栏宽度 - 分割栏宽度 - 左栏宽度 - 分割栏宽度
        var leftWidth = 'none' == qwin.page.left.css('display') ? 0 : qwin.page.left.width();
        var rightWidth = 'none' == qwin.page.right.css('display') ? 0 : qwin.page.right.width();
        var width = $(window).width() - leftWidth - rightWidth - (2 * qwin.page.splitter.width()) - 16;
        jqGridObj.jqGrid('setGridWidth', width);
    }
});
</script>
