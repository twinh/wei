<?php
/**
 * navigation-bar
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
 * @since       2011-01-03 01:17:46
 */
?>
<script type="text/javascript">
jQuery(function($){
    $('#ui-navigation-bar ul li.ui-widget').hover(
        function(){
            // 隐藏其他选项卡的内容
            $('ul.ui-navigation-content').html($(this).find('ul').html());

            // 激活当前选项卡,同时使其他选项卡恢复默认状态
            $('#ui-navigation-bar ul li.ui-widget').addClass('ui-state-default').removeClass('ui-state-hover');
            $(this).addClass('ui-state-hover');
        },function(){
    });
    var current = $('#ui-navigation-bar ul li.ui-naviagtion-bar-current');
    var currentClass = current.attr('class');
    if (undefined != currentClass) {
        if ('ui-naviagtion-bar-current' == currentClass) {
            current = current.parent().parent();
        }
        current.addClass('ui-state-hover');
        $('ul.ui-navigation-content').html(current.find('ul').html());
    }
});
</script>
<div id="ui-navigation-bar" class="ui-navigation-bar">
    <ul class="ui-widget-content">
        <li class="ui-widget ui-state-active ui-corner-top"> <a href="?"><?php echo qw_lang('LBL_QWIN') ?></a> </li>
        <?php
        foreach ($navigationData as $menu) :
            if (null == $menu['category_id']) :
                if ($menu['url'] == $queryString) :
                    $class = ' ui-naviagtion-bar-current';
                else:
                    $class = '';
                endif;
        ?>
                <li class="ui-widget ui-state-default ui-corner-top<?php echo $class ?>"> <a href="<?php echo $menu['url'] ?>" target="<?php echo $menu['target'] ?>"><?php echo $menu['title'] ?></a>
                    <ul class="ui-widget-content">
                <?php
                foreach ($navigationData as $subMenu) :
                    if ($menu['id'] == $subMenu['category_id']) :
                        if ($subMenu['url'] == $queryString) :
                            $class = ' class="ui-naviagtion-bar-current"';
                        else:
                            $class = '';
                        endif;
                ?>
                        <li<?php echo $class?>><a href="<?php echo $subMenu['url'] ?>" target="<?php echo $subMenu['target'] ?>"><?php echo $subMenu['title'] ?></a></li>
                <?php
                        endif;
                    endforeach;
                ?>
                </ul>
            </li>
        <?php
                    endif;
                endforeach;
        ?>
    </ul>
    <div class="clear"></div>
    <div class="ui-widget-content ui-state-default">
        <ul class="ui-navigation-content"></ul>
    </div>
</div>
