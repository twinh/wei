<?php
/**
 * index
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
 * @since       2011-01-10 01:12:30
 */

require $this->decodePath('<root>com/content-<action><suffix>');
?>
<script type="text/javascript">
jQuery(function($){
    var jqGridObj = $('#<?php echo $jqGridWidget->getId() ?>');
    // 还原
    $('#action-restore').click(function(){
        var keyList = new Array(),
            rowList = jqGridObj.jqGrid('getGridParam','selarrrow');
        var primaryKey = Qwin.App.primaryKey;
        if (rowList.length == 0) {
            alert(Qwin.Lang.MSG_CHOOSE_AT_LEASE_ONE_ROW);
            return false;
        }
        for (var i in rowList) {
            var rowData = jqGridObj.jqGrid('getRowData', rowList[i]);
            keyList[i] = rowData[primaryKey];
        }
        var addition = {};
        addition['action'] = 'restore';
        addition[primaryKey] = keyList.join(',');
        if (confirm(Qwin.Lang.MSG_CONFIRM_TO_RESTORE)) {
            window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        }
        return false;
    });
	// 清空
    $('#action-empty').click(function(){
        var addition = {};
		addition['action'] = 'delete';
		addition['type'] = 'all';
        if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)) {
            window.location.href = Qwin.url.createUrl(Qwin.get, addition);
        }
        return false;
    });
});
</script>
