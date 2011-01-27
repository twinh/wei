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
 * @package     Common
 * @subpackage  Namespace
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 14:37
 */

class Common_Namespace extends Qwin_Application_Namespace
{
    public function __construct()
    {
        $config = Qwin::run('-config');
        
        // 设置会话类型及启动
        if ($config['session']['enable']) {
            session_cache_limiter($config['session']['type']);
            session_start();
        }

        // 默认时区
        date_default_timezone_set($config['interface']['timezone']);

        // 关闭魔术引用
        ini_set('magic_quotes_runtime', 0);

        Qwin::setShortTag('#', 'Common_');

        // 设置页面编码
        /*if (isset($config['interface']['charset'])) {
            header('Content-Type: text/html; charset=' . $config['interface']['charset']);
        }*/

        // 设置会话
        $namespace = md5($_SERVER['SERVER_NAME'] . $config['project']['name']);
        Qwin::run('-session', $namespace);

        // 打开缓冲区
        ob_start();

        if ($config['router']['enable']) {
            $router = Qwin::run('Qwin_Url_Router');
            $router->addList($config['router']['list']);
            $url = Qwin::run('-url', $router);
        }

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
                $mainAdapter = 'localhost';
            } else {
                $mainAdapter = 'web';
            }

            $databaseSet = $config['database']['adapter'][$mainAdapter];
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
}
