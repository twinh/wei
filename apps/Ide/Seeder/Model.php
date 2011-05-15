<?php
/**
 * Model
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
 * @since       2011-04-17 13:32:25
 */

class Ide_Seeder_Model
{
    public function getModules($path = null)
    {
        
    }

    public function getModuleResource()
    {
        $resource = array();
        $lang = Qwin::call('-lang');
        $model = Com_Model::getByModule('ide/module');
        $modules = $model->getModules();
        foreach ($modules as $module) {
            if (!($meta = $this->isModuleAvailable($module['name']))) {
                continue;
            }

            $lang->appendByModule($module['name']);

            $resource[$module['name']] = array(
                'name' => $lang[$meta['page']['title']] . '(' . $module['name'] .  ')',
                'value' => $module['name'],
            );
        }
        
        return $resource;
    }

    public function isModuleAvailable($module)
    {
        $meta = Com_Meta::getByModule($module, false);
        if (!class_exists($meta)) {
            return false;
        }
        $meta = Qwin_Meta::getInstance()->get($meta);

        // TODO $meta['page']['mode'] 设定元数据模式
        // 判断是否是使用数据库的模块
        if (!isset($meta['db']) || !isset($meta['db']['table'])) {
            return false;
        }
       return $meta;
    }
}
