<?php
 /**
 * Model
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
 * @package     Widget
 * @subpackage  Model
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-04-17 15:49:35
 * @todo        Record or Model
 */

/**
 * @see Doctrine_Record
 */
require_once 'Doctrine/Record.php';

class Model_Widget extends Doctrine_Record implements Qwin_Widget_Interface
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
        // TODO 配置应该更规范
        if (!self::$_connected) {
            $manager = Doctrine_Manager::getInstance();
            $config = Qwin::config();
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
            $conn = $manager->openConnection($adapter);

            // 设置字段查询带引号
            $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

            // 设置表格式
            $manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, $db['prefix'] . '%s');

            // 设置字符集
            $conn->setCharset($db['charset']);
            //$conn->setCollate('utf8_general_ci');

            self::$_connected = true;
        }
        return true;
    }

    /**
     * 根据模块获取模型对象
     *
     * @param Qwin_Module|string $module 模块对象/标识
     * @param bool $instanced 是否实例化
     * @return Com_Model|string 实例化对象|类名
     */
    public static function getByModule($module, $instanced = true)
    {
        if ($module instanceof Qwin_Module) {
            $class = $module->getClass();
        } else {
            $class = Qwin_Module::instance($module)->getClass();
        }
        $class .= '_Model';
        if (!class_exists($class)) {
            throw new Qwin_Application_Model_Exception('Model Class "' . $class . '" not found.');
        }
        return $instanced ? new $class : $class;
    }
}
