<?php
/**
 * viewed-item
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
 * @since       2011-01-03 01:29:49
 */
?>
<div class="ui-sidebar-titlebar ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix" id="ui-sidebar-title-1">
    <span class="ui-box-title"><?php echo qw_lang('LBL_LAST_VIEWED_ITEM') ?></span>
    <a class="ui-sidebar-title-icon ui-box-title-icon ui-corner-all" name="#ui-sidebar-content-last" href="javascript:void(0)">
        <span class="ui-icon ui-icon-circle-triangle-n">open/close</span>
    </a>
</div>
<div class="ui-box-content ui-widget-content">
    <ul id="ui-sidebar-content-last">
    <?php
    if(empty($this->_data['lastViewedItem'])):
    ?>
        <li class="ui-sidebar-list ui-widget-content"><a><?php echo qw_lang('MSG_NO_LAST_VIEWED_LOG') ?></a></li>
    <?php
    else:
        foreach($this->_data['lastViewedItem'] as $row):
    ?>
        <li class="ui-sidebar-list ui-widget-content"><a href="<?php echo $row['href'] ?>"><?php echo $row['title'] ?></a></li>
    <?php
        endforeach;
    endif;
    ?>
    </ul>
</div>