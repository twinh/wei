<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * Query
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2010-04-17 15:49:35
 */
class Qwin_Record extends Doctrine_Record
{
    /**
     * 数据库连接标识
     * @var bool
     */
    protected static $_connected = false;

    public $__invoker;

    public static $dbOptions = array(
        'type'      => 'mysql',
        'server'    => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'database'  => 'qwin',
        'prefix'    => 'qwin_',
        'port'      => 3306,
        'charset'   => 'utf8',
        'collate'   => 'utf8_general_ci',
    );

    public function __construct($table = null, $isNewEntry = false)
    {
        // 保证连接数据库链接上,否则出现异常
        self::connect((array)$table + self::$dbOptions);

        parent::__construct($table, $isNewEntry);
    }

    /**
     * Get module's record object
     *
     * @param string $module the name of the module
     * @param string $name the type of the record, as a part of the module class name
     * @return Qwin_Record
     */
    public function __invoke($module = null, $type = null)
    {
        $widget = Qwin::getInstance();
        if (!$module) {
            $module = $widget->module();
        }
        $class = ucfirst($module) . '_' . ucfirst($type) . 'Record';
        return $widget->__invoke($class);
    }

    /**
     * 连接数据库
     *
     * @return void
     */
    public static function connect(array $options = array())
    {
        if (!self::$_connected) {

            // 通过Qwin_Query等外部类调用时,参数可能为空,可自行获取
            if (empty($options)) {
               $options = current(Qwin::getInstance()->config('Qwin_Record')) + self::$dbOptions;
            }

            $manager = Doctrine_Manager::getInstance();
            $adapter = $options['type'] . '://'
                     . $options['username'] . ':'
                     . $options['password'] . '@'
                     . $options['server'] . '/'
                     . $options['database'];
            $conn = $manager->openConnection($adapter);

            // 设置字段查询带引号
            $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

            // 设置数据表名称格式
            $manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, $options['prefix'] . '%s');

            // 设置字符集
            $conn->setCharset($options['charset']);
            $conn->setCollate($options['collate']);

            self::$_connected = true;
        }
    }

    /**
     * 覆盖父类方法,增加返回当前对象
     *
     * @return Qwin_Record
     */
    public function fromArray(array $array, $deep = true)
    {
        parent::fromArray($array, $deep);
        return $this;
    }
}
