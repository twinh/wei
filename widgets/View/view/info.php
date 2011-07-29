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
<div class="qw-content ui-widget-content">
    <div class="qw-content-content qw-message ui-box-content ui-widget-content">
        <div class="qw-message-box ui-state-highlight ui-corner-all">
            <div class="qw-message-icon">
                <span class="qw-icon qw-icon-<?php echo $icon ?>-64"></span>
            </div>
            <div class="qw-message-content">
                <h4><?php echo $title ?></h4>
                <?php
                    if (is_array($content)) :
                ?>
                <ol>
                    <?php
                    foreach ($content as $row) :
                    ?>
                    <li><?php echo $row ?></li>
                    <?php
                    endforeach;
                    ?>
                </ol>
                <?php
                    elseif (isset($content)) :
                ?>
                <p><?php echo $content ?></p>
                <?php
                    endif;
                    if (isset($url)) :
                ?>
                <script type="text/javascript">
                    window.setTimeout(function(){
                        window.location.href = '<?php echo str_replace('\'', '\\\'', $url) ?>';
                    }, <?php echo $time ?>);
                </script>
                <?php
                    else:
                        $url = 'javascript:history.go(-1);';
                    endif;
                ?>
                <p>&nbsp;</p>
                <p><a class="qw-message-link" href="<?php echo $url ?>"><?php echo qw_t('MSG_CLICK_TO_REDIRECT') ?></a></p>
            </div>
        </div>
        <div class="qw-message-operation">
            <a class="qw-anchor" href="javascript:history.go(-1);" data="{icons:{primary:'ui-icon-arrowthickstop-1-w'}}"><?php echo $lang['ACT_RETURN'] ?></a>
        </div>
    </div>
</div>