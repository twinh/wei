<?php
/**
 * Manager
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-7-26 13:15:23
 * @since     2010-7-26 13:15:23
 */

/**
 * metadata数据管理器
 */

class Qwin_Metadata_Manager
{
    /**
     * 存放所有元数据
     * @var <type>
     */
    private $_data;

    /**
     * 存放所有元数据的原始数据
     * @var <type>
     */
    private $_originalData;

    /**
     * 存储各metada实例化的数组
     * @var <type>
     */
    private static $_metadataObj;

    /**
     * 将一组元数据加入管理器中
     *
     * @param <type> $name
     * @param <type> $metadata
     * @example $metadataManager->set('Default_Article_Metadata_Article', $meta->defaultMetadata);
     */
    public function set($name, $metadata)
    {
        if(!isset($_originalData[$name]))
        {
            $this->_data[$name] = $metadata;
            $this->_originalData = $metadata;
        }
    }

    public static function get($className)
    {
        if(isset(self::$_metadataObj[$className]))
        {
            return self::$_metadataObj[$className];
        }
        if(class_exists($className, true))
        {
            self::$_metadataObj[$className] = new $className;
            self::$_metadataObj[$className]->setMetadata();
            return self::$_metadataObj[$className];
        }
        return NULL;
    }
}

