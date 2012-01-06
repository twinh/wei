<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * index
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-12-14 22:32:30
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->getCore(),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $jQuery->get('ui.core, effects, qui, ui.button, metadata, jqGrid'),
    $this->getFile('views/style.css'),
    $this->getFile('views/style.js'),
));
?>
</head>
<body>
<style type="text/css">
    #gbox_ui-jqgrid-1 {
        border-left: 0;
        border-right: 0;
    }
    #qw-jqgrid-top {
        font-weight: normal;
        padding: 3px 0 0 5px;
        border-width: 0 0 0 0;
        overflow: hidden;
        line-height: 20px;
    }
    .ui-jqgrid .ui-jqgrid-bdiv {
        overflow-x: hidden; 
    }
    #ui-jqgrid-1-pager {
        border-bottom-width: 0;
    }
</style>
<div id="qw-jqgrid-top" class="ui-state-default">
    <a href="#">添加</a> | <a href="#">编辑</a> | <a href="#">删除</a>
</div>
<div class="qw-c"></div>
<table id="<?php echo $jqGrid['id'] ?>"></table>
<div id="<?php echo $jqGrid['pager'] ?>"></div>
<script type="text/javascript">
jQuery(function($){
    var jqGrid = <?php echo json_encode($jqGrid)?>;
    // todo 如何在php代码中表示js function
    jqGrid.ondblClickRow = function(){};
    $('#<?php echo $jqGrid['id'] ?>')
        .jqGrid(jqGrid)
            .jqGrid('navGrid', jqGrid.pager,{
            add : false,
            edit : false,
            del : false,
            search : false
        });

    // 样式调整
    var gboxId = '#gbox_<?php echo $jqGrid['id'] ?>';
    //$(gboxId).width($(gboxId).width() - 2).addClass('ui-state-default');
    $('table.ui-jqgrid-htable tr.ui-jqgrid-labels th:last').css('border-right', 'none');
    $('#t_ui-jqgrid-1').append($('#qw-jqgrid-top').html());
    var primaryKey = 'id';
    var jqGridObj = qwin.jqGrid = $('#<?php echo $jqGrid['id'] ?>');

    jqGridObj.jqGrid('setGridParam',{
        ondblClickRow: function(rowId, iRow, iCol, e){
            var rowData = jqGridObj.jqGrid('getRowData', rowId),
                addition = {};
            addition['action'] = 'view';
            addition[primaryKey] = rowData[primaryKey];
            window.location.href = qwin.url.createUrl(qwin.get, addition);
            return false;
    }})

    
    function setGridHeight(jqGridObj) {
        var height = $('body').height()
            - $('#qw-jqgrid-top').outerHeight()
            - $('#gview_ui-jqgrid-1 .ui-jqgrid-hdiv').outerHeight()
            - $('#ui-jqgrid-1-pager').outerHeight();
        jqGridObj.setGridHeight(height);
    }
    
    $(window).load(function(){
        setGridHeight(jqGridObj);
    }).resize(function(){
        jqGridObj.setGridWidth($(window).width());
        setGridHeight(jqGridObj);
    });
    
    // 点击删除按钮
    $('#action-<?php echo $jqGrid['id'] ?>-delete').click(function(){
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
    $('#action-<?php echo $jqGrid['id'] ?>-copy').click(function(){
        var rowList = jqGridObj.jqGrid('getGridParam','selarrrow');
        if (rowList.length != 1) {
            alert(qwin.lang.MSG_CHOOSE_ONLY_ONE_ROW);
            return false;
        }
        var rowData = jqGridObj.jqGrid('getRowData', rowList[0]);
        var url = $(this).attr('href') + '&amp;' + primaryKey + '=' + rowData[primaryKey];
        window.location.href = url;
        return false;
    });
});
</script>
</body>
</html>
