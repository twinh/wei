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
 * @subpackage  Record
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-04-17 15:49:35
 */

/**
 * @see Doctrine_Record
 */
require_once 'Doctrine/Record.php';

class Record_Widget extends Doctrine_Record implements Qwin_Widget_Interface
{
    /**
     * 数据库连接标识
     * @var bool
     */
    protected static $_connected = false;
    
    /**
     * 记录类名和模块,数据库元数据名称的对应表
     * 
     * @var array 
     */
    protected static $_recordMap = array();
    
    public function __construct($table = null, $isNewEntry = false)
    {
        // 保证连接数据库链接上,否则出现异常
        self::connect();

        parent::__construct($table, $isNewEntry);
    }
    
    /**
     * 设置字段属性和关联关系
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $class = get_class($this);
        if (isset(self::$_recordMap[$class])) {
            // 获取数据库元数据
            $meta = Meta_Widget::getByModule(self::$_recordMap[$class]['module'])
                ->get(self::$_recordMap[$class]['name']);
            
            // 设置数据表
            $this->setTableName($meta['table']);

            // 设置字段
            foreach ($meta['fields'] as $name => $field) {
                if ($field['dbField'] && $field['dbQuery']) {
                    $this->hasColumn($name);
                }
            }

            // 设置关联
            foreach ($meta['relations'] as  $relation) {
                $class = self::getByModule($relation['module'], $relation['db'], false);
                call_user_func(
                    array($this, $relation['relation']),
                    $class . ' as ' . $relation['alias'],
                    array(
                        'local' => $relation['local'],
                        'foreign' => $relation['foreign']
                    )
                );
            }
        }
    }

    /**
     * 连接数据库
     *
     * @return void
     */
    public static function connect()
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
        return;
    }

    /**
     * 根据模块获取记录对象
     * 
     * @param Qwin_Module|string $module 模块对象/标识
     * @param string $name 元数据数据库标识
     * @param bool $instanced 是否实例化
     * @return Record_Widget|string
     */
    public static function getByModule($module, $name = 'Db', $instanced = true)
    {
        // 类名前缀,即模块标识
        if (!$module instanceof Qwin_Module) {
            $module = Qwin_Module::instance($module);
        }
        $class = $module->getClass();
        
        // 数据库元数据名称,一般为Db
        $class .= '_'; 
        if ($name) {
            $class .= ucfirst($name);
        }
        
        // 类名后缀
        $class .= 'Record';
        
        if (!class_exists($class)) {
            throw new Qwin_Widget_Exception('Record class "' . $class . '" not found.');
        }
        
        // 将模块和数据库标识存储
        if (!isset(self::$_recordMap[$class])) {
            self::$_recordMap[$class] = array(
                'module' => $module,
                'name' => strtolower($name),
            );
        }
        
        return $instanced ? Qwin::call($class) : $class;
    }
}
