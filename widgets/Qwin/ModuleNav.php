<?php
/**
 * Widget
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-03-27 01:42:40
 */

class Qwin_ModuleNav extends Qwin_Widget
{
    public function triggerBeforeContentLoad()
    {
        $module = $this->module();
        $action = $this->get('action', 'index');
        $view = $this->view;
        $lang = $this->lang;
        $url = $this->url;
        
        $icon = $view->getFile('apps/icons/document_32.png');
        $moduleUrl = $url->url->url($module->toUrl());
        $moduleTitle = $lang[$module->toString()];
        
        $actionUrl = $url->build();
        $actionTitle = $lang[$action->toString()];
        
        $this->minify->add($view->getFile('widgets/modulenav/default.css'));
        require $view->getFile('widgets/modulenav/default.php');
    }
}
