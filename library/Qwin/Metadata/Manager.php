<?php
/**
 * 元数据管理器
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
 * @package     Qwin
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-26 13:15:23
 */

class Qwin_Metadata_Manager
{
    /**
     * 存放所有元数据
     * @var array
     */
    private $_data;

    /**
     * 存储各元数据实例化对象的数组
     * @var array
     */
    private static $_metadataObj;

    /**
     * 将一组元数据加入管理器中
     *
     * @param string $name
     * @param Qwin_Metadata $metadata
     * @example $metadataManager->set('Trex_Article_Metadata_Article', $meta->defaultMetadata);
     */
    public function set($name, $metadata)
    {
        if(!isset($this->_data[$name]))
        {
            $this->_data[$name] = $metadata;
        }
        return $this;
    }

    /**
     * 加载一个元数据类
     *
     * @param string $className 类名称
     * @return object 元数据类对象
     */
    public static function get($className)
    {
        if(isset(self::$_metadataObj[$className]))
        {
            return self::$_metadataObj[$className];
        }

        if(class_exists($className))
        {
            if(is_subclass_of($className, 'Qwin_Metadata'))
            {
                self::$_metadataObj[$className] = new $className;
                self::$_metadataObj[$className]->setMetadata();
                return self::$_metadataObj[$className];
            }

            require_once 'Qwin/Trex/Metadata/Exception.php';
            throw new Qwin_Trex_Metadata_Exception('The class ' . $className . ' is not the sub class of Qwin_Metadata');
        }

        require_once 'Qwin/Trex/Metadata/Exception.php';
        throw new Qwin_Trex_Metadata_Exception('Can not find the class ' . $className);
    }
}

