<?php
/**
 * Namespace
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @subpackage  Namespace
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 14:37
 */

class Trex_Namespace extends Qwin_Trex_Namespace
{
    public function __construct()
    {
        $ini = Qwin::run('Qwin_Trex_Setup');
        $this->_config = $ini->getConfig();
        $config = &$this->_config;

        /**
         * 设置会话
         */
        Qwin::addMap('-ses', 'Qwin_Session');
        $namespace = md5($_SERVER['SERVER_NAME'] . $this->_config['project']['name']);
        Qwin::run('-ses', $namespace);

        /**
         * 打开缓冲区
         */
        ob_start(); 

        /**
         * 更新类库缓存文件
         */
        if(isset($_GET['_update']))
        {
            // 加入类的路径
            Qwin::addMultiPath(array(
                QWIN_LIB_PATH => 10,
                QWIN_RESOURCE_PATH . DS . 'application' => 10,
                QWIN_ROOT_PATH . DS . 'application' => 10,
            ));
            Qwin::update();
        }

        /**
         * 设置类的对应关系
         */
        Qwin::addMap(array(
            // 权限控制
            '-acl'  => 'Qwin_Acl',
            '-arr'  => 'Qwin_Helper_Array',
            '-url'  => 'Qwin_Url',
            '-ini'  => 'Qwin_Trex_Setup',

            // 前端数据处理
            '-wfe'  => 'Qwin_Wfe',
            '-js'   => 'Qwin_Wfe_Js',
            '-css'  => 'Qwin_Wfe_Css',
            '-html' => 'Qwin_Wfe_Html',
            '-rsc'  => 'Qwin_Wfe_Resource',

            // 表单生成
            '-form' => 'Qwin_Form',

            '-str'  => 'Qwin_converter_String',
        ));

        /**
         * 加载表单,按钮生成类
         */
        Qwin::run('-form')
            ->add('Qwin_Form_Element_Base');
       
        /**
         * 数据库链接,使用的是Doctrine Orm
         * @todo 助手类
         */
        if($config['database']['setup'])
        {
            if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
            {
                $config['database']['mainAdapter'] = 'localhost';
            } else {
                $config['database']['mainAdapter'] = 'web';
            }
            // 更新配置数据
            Qwin::run('-ini')->setConfig($config);

            // TEST 0924
            //registerConnectionDriver
            Doctrine_Manager::getInstance()->registerConnectionDriver('padb', 'Doctrine_Connection_Padb');

            //require_once(QWIN_LIB_PATH . '/Doctrine.php');
            //spl_autoload_register(array('Doctrine', 'autoload'));
            $databaseSet = $config['database']['adapter'][$config['database']['mainAdapter']];
            $adapter = $databaseSet['type'] . '://'
                     . $databaseSet['username'] . ':'
                     . $databaseSet['password'] . '@'
                     . $databaseSet['server'] . '/'
                     . $databaseSet['database'];
            $conn = Doctrine_Manager::connection($adapter, $config['project']['name']);
            $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
            $conn->setCharset($databaseSet['charset']);
        }
    }

    public function  __destruct()
    {
        /**
         * 获取缓冲数据,输出并清理
         */
        $output = ob_get_contents();
        '' != $output && ob_end_clean();
        echo $output;
        unset($output);
    }
}