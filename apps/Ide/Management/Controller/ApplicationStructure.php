<?php
/**
 * ApplicationStructure
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
 * @package     Com
 * @subpackage  Management
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-11 21:15:01
 */

class Com_Management_Controller_ApplicationStructure extends Com_Controller
{
    public function actionIndex()
    {
        $meta = $this->_meta;
        $this->view->assign(get_defined_vars());
    }

    /**
     * 更新应用目录结构缓存文件,权限分配页面可以根据应用目录的结构进行权限分配
     */
    public function actionUpdate()
    {
        $app = Qwin::call('Qwin_Application_Application');

        // TODO 多路径的支持
        $path = array(
            realpath($app->getRootAppPath()),
            realpath($app->getDefultPath()),
        );
        if($path[0] == $path[1])
        {
            unset($path[1]);
        }

        $action = array();
        foreach($path as $subPath)
        {
            $package = $app->getPackage($subPath);
            $module = $app->getModule($subPath, $package);
            $controller = $app->getController($subPath, $module);
            $action += $app->getAction($subPath, $controller);
        }

        Qwin_Helper_File::writeArray(Qwin_ROOT_PATH . '/cache/php/application-structure.php', $action);

        $url = Qwin::call('-url')->url($this->_asc, array('action' => 'Index'));
        $this->view->redirect($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
    }

    public function validatePackage($value, $name, $data)
    {
        $path = $this->_getAppPath();
        $namesapceList = $this->_getPackage($path);
        if(!in_array($value, $namesapceList))
        {
            return true;
        }
        return new Qwin_Validator_Result(false, 'package', 'MSG_VALIDATOR_NAMESPACE_EXISTS');
    }
}
