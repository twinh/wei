<?php
/**
 * 主题设置
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
 * @since       2010-05-23 00:34:08
 */
?>
<script type="text/javascript">
jQuery(function($){
    $('div.ui-image-list ul li').qui();
});
</script>
<div class="qw-center ui-widget-content">
    <div class="qw-center-content">
        <form action="" method="post">
        <div class="ui-form-content ui-box-content ui-widget-content ui-image-list">
            <div class="ui-theme-operation ui-operation-field">
                <button type="submit" class="qw-button" data="{icons:{primary:'ui-icon-check'}}"><?php echo $lang['ACT_SUBMIT'] ?></button>
                <a class="qw-anchor" href="javascript:history.go(-1);" data="{icons:{primary:'ui-icon-arrowthickstop-1-w'}}"><?php echo $lang['ACT_RETURN'] ?></a>
            </div>
            <hr class="ui-line ui-widget-content" />
            <ul>
    <?php
    foreach($styles as $row){
        $image = $path . '/' . $row['path'] . '/images/' . $row['image'];
        $url = qw_u($module['url'], 'style', array('style' => $row['path']));
    ?>
                <li class="ui-widget-content ui-corner-all">
                    <a href="<?php echo $url?>">
                        <img alt="<?php echo $row['name']?>" src="<?php echo $image ?>" />
                    </a>
                    <p>
                        <a href="<?php echo $url ?>" title="<"><?php echo $row['name']?></a>

                    </p>
                </li>
    <?php
    }
    ?>
            </ul>
            <hr class="ui-line ui-widget-content" />
            <div class="ui-theme-operation ui-operation-field">
                <button type="submit" class="qw-button" data="{icons:{primary:'ui-icon-check'}}"><?php echo $lang['ACT_SUBMIT'] ?></button>
                <a class="qw-anchor" href="javascript:history.go(-1);" data="{icons:{primary:'ui-icon-arrowthickstop-1-w'}}"><?php echo $lang['ACT_RETURN'] ?></a>
            </div>
        </div>
        </form>
        <div class="ui-space-10px"></div>
    </div>
</div>