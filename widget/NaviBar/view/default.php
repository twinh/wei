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
    var navbarUl;
    $('#ui-nav-bar ul li.ui-widget').hover(
        function(){
            $(this).addClass('ui-state-hover');
            navbarUl = $(this).find('ul');
            if ('' != $.trim(navbarUl.html()) && 'none' == navbarUl.css('display')) {
                navbarUl.show(300);
            }
        }, function(){
            $(this).removeClass('ui-state-hover');
            if ('none' != navbarUl.css('display')) {
                navbarUl.hide(300);
            }
        }
    );
});
</script>
<div id="ui-nav-bar" class="ui-nav-bar">
    <ul class="ui-widget-content ui-navbar-parent">
        <li class="ui-widget ui-state-active ui-corner-top"> <a href="?"><?php echo qw_t('LBL_QWIN') ?></a>
        </li><?php
        foreach ($navigationData as $menu) :
            if (null == $menu['category_id']) :
        ?><li class="ui-widget ui-state-default ui-corner-top"> <a href="<?php echo $menu['url'] ?>" target="<?php echo $menu['target'] ?>"><?php echo $menu['title'] ?></a>
        <ul class="ui-state-hover ui-corner-bottom">
            <?php
            foreach ($navigationData as $subMenu) :
                if ($menu['id'] == $subMenu['category_id']) :
            ?>
                    <li><a href="<?php echo $subMenu['url'] ?>" target="<?php echo $subMenu['target'] ?>"><?php echo $subMenu['title'] ?></a></li>
            <?php
                    endif;
                endforeach;
            ?>
            </ul>
        </li><?php
                    endif;
                endforeach;
        ?>
    </ul>
</div>
