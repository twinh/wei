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
<div id="ui-top-floating-bar" class="ui-top-floating-bar ui-widget ui-widget-header">
	<ul>
    	<li class="ui-top-bar-list">
            <a href="?" class="ui-widget ui-state-active ui-corner-all"><?php echo qw_lang('LBL_QWIN') ?></a>
        </li>
        <?php
        foreach($this->_data['navigationData'] as $menu) :
            if(null == $menu['category_id']) :
        ?>
        <li class="ui-top-bar-list">
            <a href="<?php echo $menu['url'] ?>" target="<?php echo $menu['target'] ?>" class="ui-widget ui-state-default ui-corner-all"><?php echo $menu['title'] ?></a>
            <ul class="ui-state-hover ui-corner-bottom ui-corner-tr">
                <?php
                foreach($this->adminMenu as $subMenu) :
                    if($menu['id'] == $subMenu['category_id']) :
                ?>
                <li><a href="<?php echo $subMenu['url'] ?>" target="<?php echo $subMenu['target'] ?>"><?php echo $subMenu['title'] ?></a></li>

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
</div>