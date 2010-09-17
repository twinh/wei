<?php
/**
 * ApplicationStructure
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
 * @subpackage  Management
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-11 21:15:01
 */

class Trex_Management_Controller_ApplicationStructure extends Trex_Controller
{
    public function actionIndex()
    {
        $theme = Qwin::run('-ini')->getConfig('interface.theme');
        $this->_view = array(
            'class' => 'Trex_View',
            'element' => array(
                array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/management/application-structure.php'),
            ),
            'data' => get_defined_vars(),
        );
    }


    /**
     * 更新应用目录结构缓存文件,权限分配页面可以根据应用目录的结构进行权限分配
     */
    public function actionUpdate()
    {
        $app = Qwin::run('Qwin_Trex_Application');
        $path = $app->getDefultPath();
        $namespace = $app->getNamespace($path);
        $module = $app->getModule($path, $namespace);
        $controller = $app->getController($path, $module);
        $action = $app->getAction($path, $controller);

        Qwin_Helper_File::writeAsArray($action, QWIN_ROOT_PATH . '/cache/php/application-structure.php');

        $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
        $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
    }

    public function validateNamespace($value, $name, $data)
    {
        $path = $this->_getAppPath();
        $namesapceList = $this->_getNamespace($path);
        if(!in_array($value, $namesapceList))
        {
            return true;
        }
        return new Qwin_Validator_Result(false, 'namespace', 'MSG_VALIDATOR_NAMESPACE_EXISTS');
    }
}
