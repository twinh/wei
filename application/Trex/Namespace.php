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

        // 设置页面编码
        if(isset($config['interface']['charset']))
        {
            header('Content-Type: text/html; charset=' . $config['interface']['charset']);
        }

        // 设置会话
        $namespace = md5($_SERVER['SERVER_NAME'] . $this->_config['project']['name']);
        Qwin::run('-session', $namespace);

        // 打开缓冲区
        ob_start();

        // 更新类库缓存文件
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

        // 设置类的对应关系
        Qwin::addMap(array(
            '-arr'  => 'Qwin_Helper_Array',
            '-ini'  => 'Qwin_Trex_Setup',

            // 前端数据处理
            '-wfe'  => 'Qwin_Wfe',
            '-js'   => 'Qwin_Wfe_Js',
            '-css'  => 'Qwin_Wfe_Css',
            '-html' => 'Qwin_Wfe_Html',
            '-rsc'  => 'Qwin_Wfe_Resource',

            '-str'  => 'Qwin_Converter_String',
        ));
       
        /**
         * 数据库链接,使用的是Doctrine Orm
         * @todo 助手类
         */
        $manager = Doctrine_Manager::getInstance();
        
        // 连接padb数据库
        if(isset($config['database']['adapter']['padb']))
        {
            $manager->registerConnectionDriver('padb', 'Doctrine_Connection_Padb');
            $manager->registerHydrator('padb', 'Doctrine_Hydrator_Padb');
            $padb = $config['database']['adapter']['padb'];
            $adapter = $padb['type'] . '://'
                     . $padb['username'] . ':'
                     . $padb['password'] . '@'
                     . $padb['server'] . '/'
                     . $padb['database'];
            $conn = Doctrine_Manager::connection($adapter, 'padb');
        }

        // 连接其他数据库
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

            $databaseSet = $config['database']['adapter'][$config['database']['mainAdapter']];
            $adapter = $databaseSet['type'] . '://'
                     . $databaseSet['username'] . ':'
                     . $databaseSet['password'] . '@'
                     . $databaseSet['server'] . '/'
                     . $databaseSet['database'];
            $conn = Doctrine_Manager::connection($adapter, $config['project']['name']);
            $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
            $conn->setCharset($databaseSet['charset']);
            //$conn->setCollate('utf8_general_ci');
        }
    }

    public function  __destruct()
    {
        /**
         * 获取缓冲数据,输出并清理
         */
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        // TODO
        $search = array(
            '<!-- Qwin_Packer_Css -->',
            '<!-- Qwin_Packer_Js -->',
        );
        $replace = array(
            Qwin::run('Qwin_Packer_Css')->pack()->getHtmlTag(),
            Qwin::run('Qwin_Packer_Js')->pack()->getHtmlTag(),
        );
        $output = str_replace($search, $replace, $output);

        echo $output;
        unset($output);
    }
}