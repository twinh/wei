<?php
/**
 * Model
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
 * @subpackage  Model
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-28 17:26:42
 */

require_once 'Qwin/Application/Model.php';

class Common_Model extends Qwin_Application_Model
{
    /**
     * 数据库连接标识
     * @var bool
     */
    protected static $_connected = false;
    
    public function  __construct($table = null, $isNewEntry = false)
    {
        self::_connect();
        parent::__construct($table, $isNewEntry);
    }

    /**
     * 连接数据库
     *
     * @return bool
     */
    protected static function _connect()
    {
        if (!self::$_connected) {
            $manager = Doctrine_Manager::getInstance();
            $config = Qwin::config();
            // 连接padb数据库
            /*if(isset($config['database']['adapter']['padb'])) {
                $manager->registerConnectionDriver('padb', 'Doctrine_Connection_Padb');
                $manager->registerHydrator('padb', 'Doctrine_Hydrator_Padb');
                $padb = $config['database']['adapter']['padb'];
                $adapter = $padb['type'] . '://'
                         . $padb['username'] . ':'
                         . $padb['password'] . '@'
                         . $padb['server'] . '/'
                         . $padb['database'];
                $conn = $manager->openConnection($adapter, 'padb');
            }*/

            // 连接其他数据库
            if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
                $mainAdapter = 'localhost';
            } else {
                $mainAdapter = 'web';
            }
            $db = $config['database']['adapter'][$mainAdapter];
            $adapter = $db['type'] . '://'
                     . $db['username'] . ':'
                     . $db['password'] . '@'
                     . $db['server'] . '/'
                     . $db['database'];
            $conn = $manager->openConnection($adapter, $config['projectName']);

            // 设置字段查询带引号
            $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

            // 设置表前缀
            $manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, $db['prefix'] . '%s');

            // 设置字符集
            $conn->setCharset($db['charset']);
            //$conn->setCollate('utf8_general_ci');

            self::$_connected = true;
        }
        return true;
    }
}