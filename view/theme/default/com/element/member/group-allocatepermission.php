<?php
/**
 * memberpermission
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
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-12 21:06:09
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<style type="text/css">
.ui-permission-list{
    padding: 1em;
}
.ui-app-structure-package{

}
.ui-app-structure-module td{
    padding: 2em 0 0 2em;
}
.ui-app-structure-controller td{
    padding-left: 4em;
}
.ui-app-structure-action td{
    padding-left: 6em;
}
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
    	<?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-theme-operation ui-operation-field">
            <?php echo qw_jQuery_button('submit', qw_lang('ACT_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jQuery_link('javascript:history.go(-1);', qw_lang('ACT_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
        </div>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-permission-list">
            <table>
            <?php
            foreach($appStructure as $package => $moduleList):
                if(isset($permission[$package])):
                    $checked = ' checked="checked" ';
                else :
                    $checked = '';
                endif;
            ?>
                <tr class="ui-app-structure-package">
                    <td class="ui-field-checkbox">
                        <label for="package-<?php echo $package ?>"><?php echo $package ?></label><input type="checkbox" id="package-<?php echo $package ?>" name="permission[<?php echo $package ?>]" <?php echo $checked ?> />
                    </td>
                </tr>
            <?php
                foreach($moduleList as $module => $controllerList):
                    if(isset($permission[$package . '|' . $module])):
                        $checked = ' checked="checked" ';
                    else :
                        $checked = '';
                    endif;
            ?>
                <tr class="ui-app-structure-module">
                    <td class="ui-field-checkbox">
                        <label for="module-<?php echo $package ?>-<?php echo $module ?>"><?php echo $module ?></label><input type="checkbox" id="module-<?php echo $package ?>-<?php echo $module ?>" name="permission[<?php echo $package ?>|<?php echo $module ?>]" <?php echo $checked ?> />
                    </td>
                </tr>
            <?php
                    foreach($controllerList as $controller => $actionList):
                        if(isset($permission[$package . '|' . $module . '|' . $controller])):
                            $checked = ' checked="checked" ';
                        else :
                            $checked = '';
                        endif;
            ?>
                <tr class="ui-app-structure-controller">
                    <td class="ui-field-checkbox">
                        <label for="controller-<?php echo $package ?>-<?php echo $module ?>-<?php echo $controller ?>"><?php echo $controller ?></label><input type="checkbox" id="controller-<?php echo $package ?>-<?php echo $module ?>-<?php echo $controller ?>" name="permission[<?php echo $package ?>|<?php echo $module ?>|<?php echo $controller ?>]" <?php echo $checked ?> />
                    </td>
                </tr>
                <tr class="ui-app-structure-action">
                    <td class="ui-field-checkbox">
            <?php
                        foreach($actionList as $action):
                            if(isset($permission[$package . '|' . $module . '|' . $controller . '|' . $action])):
                                $checked = ' checked="checked" ';
                            else :
                                $checked = '';
                            endif;
            ?>
                
                        <label for="action-<?php echo $package ?>-<?php echo $module ?>-<?php echo $controller ?>-<?php echo $action ?>"><?php echo $action ?></label><input type="checkbox" id="action-<?php echo $package ?>-<?php echo $module ?>-<?php echo $controller ?>-<?php echo $action ?>" name="permission[<?php echo $package ?>|<?php echo $module ?>|<?php echo $controller ?>|<?php echo $action ?>]" <?php echo $checked ?> />
            <?php
                        endforeach;
            ?>
                        </td>
                </tr>
            <?php
                    endforeach;
                endforeach;
            endforeach;
            ?>
            </table>
        
    </div>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-theme-operation ui-operation-field">
            <?php echo qw_jQuery_button('submit', qw_lang('ACT_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jQuery_link('javascript:history.go(-1);', qw_lang('ACT_RETURN'), 'ui-icon-arrowthickstop-1-w') ?>
        </div>
        
    </div>
    </form>
</div>
