<?php
/**
 * 跳转
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
 * @subpackage  Common
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-26 11:01:20
 */
?>
<div class="ui-box ui-widget ui-widget-content ui-corner-all">
    <div class="ui-box-titlebar ui-widget-header ui-helper-clearfix">
        <span class="ui-box-title"><?php echo qw_lang('LBL_MESSAGE') ?></span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-message-content" href="javascript:void(0)"> <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span> </a> </div>
    <div class="ui-message-content ui-box-content ui-widget-content">
        <div class="ui-state-highlight ui-corner-all">
            <p>
                <span class="ui-icon ui-icon-info"></span>
                <?php echo $message ?>
            </p>
        <?php
            if(isset($url)) :
        ?>
            <p>
                <span class="ui-icon ui-icon-info"></span>
                <?php echo qw_lang('MSG_CLICK_TO_REDIRECT') ?>
            </p>
            <script type="text/javascript">
            window.setTimeout(function(){
                window.location.href = '<?php echo str_replace('\'', '\\\'', $url) ?>';
            }, 3000);
            </script>
        <?php
            endif;
        ?>
        </div>
        <div class="ui-message-operation">
        <?php
            if(isset($url)) :
        ?>
            <a class="ui-action-redirect" href="<?php echo $url ?>"><?php echo qw_lang('LBL_ACTION_REDIRECT') ?></a>
        <?php
            endif;
        ?>
        <?php echo qw_jquery_link('javascript:history.go(-1);', qw_lang('LBL_ACTION_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
        </div>
    </div>
</div>