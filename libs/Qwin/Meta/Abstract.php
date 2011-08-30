<?php
/**
 * Access
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
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-27 15:41:46
 */

abstract class Qwin_Meta_Abstract extends ArrayObject
{
    /**
     * 管理类
     *
     * @var Qwin_Meta
     */
    protected $_manager = null;

    /**
     * 初始化类
     *
     * @param array $input 数据
     */
    public function  __construct($input = array())
    {
        parent::__construct($input, self::ARRAY_AS_PROPS);
    }

    /**
     * 设置基本元数据,初始化时调用该方法
     *
     * @return Qwin_Meta_Abstract 当前对象
     */
    public function setMeta()
    {
        return $this;
    }

    /**
     * 获取管理器对象
     *
     * @return Qwin_Meta
     */
    public function getManager()
    {
        return $this->_manager;
    }

    /**
     * 设置管理器
     *
     * @param Qwin_Meta $meta 管理器对象
     * @return Qwin_Meta_Abstract 当前对象
     */
    public function setManager(Qwin_Meta $meta)
    {
        $this->_manager = $meta;
        return $this;
    }

    /**
     * 获取元数据的数组形式(仅获取至第二层)
     *
     * @return 数组
     */
    public function toArray()
    {
        $array = $this->getArrayCopy();
        foreach ($array as $name => $element) {
            if (is_subclass_of($element, 'ArrayObject')) {
                $array[$name] = $element->getArrayCopy();
            }
        }
        return $array;
    }

    /**
     * 将数组加入元数据中
     *
     * @param array $data 数组
     * @return Qwin_Meta_Abstract 当前对象
     */
    public function fromArray(array $data)
    {
        $this->exchangeArray(array_merge($this->getArrayCopy(), $data));
        return $this;
    }

    /**
     * 合并/附加元数据
     *
     * @param string $index 键名
     * @param array $data 数据
     * @return QwiQwin_Meta_Abstract 当前对象
     */
    public function merge($index, $data)
    {
        if (!$this->offsetExists($index)) {
            return $this->set($index, $data);
        }
        $this[$index]->merge($data);
        return $this;
    }

    /**
     * 设置元数据的值
     *
     * @param string $index 键名
     * @param array $data 数据
     * @param string $driver 驱动标识
     * @todo 驱动标识支持多种类型,如类名,对象
     */
    public function set($index, $data = null, $driver = null)
    {
        $meta = Qwin_Meta::getInstance();

        // 获取驱动类名 $driver > $index > default
        if (isset($driver)) {
            $class = $meta->getDriver($driver);
            if (!$class) {
                throw new Qwin_Meta_Exception(sprintf('Meta driver "%s" not found.', $driver));
            }
        } else {
            $class = $meta->getDriver($index);
            if (!$class) {
                $class = $meta->getDefaultDriver();
            }
        }

        // 不存在该键名,直接设置值
        if (!$this->offsetExists($index)) {
            $this[$index] = new $class;
            $this[$index]->setParent($this);
            $this[$index]->merge($data);
        } else {
            // 元数据该键名的值不是对象
//            if (!is_object($this[$index])) {
//                $data = $data + $this[$index];
//                $this[$index] = new $class;
//                $this[$index]->merge($data);
//            } else {
                // 类名一样,直接设置值
                if ($class == get_class($this[$index])) {
                    $this[$index]->merge($data);
                // 类名不一样,取出原始数据重新设置
                } else {
                    $data = $data + $this[$index]->getArrayCopy();
                    $this[$index] = new $class;
                    $this[$index]->setParent($this);
                    $this[$index]->merge($data);
                }
//            }
        }

        return $this;
    }

    /**
     * 获取元数据唯一编号
     *
     * @return string 编号
     * @todo ..
     */
    public function getId()
    {
        if (!isset($this->_id)) {
            // 如果存在数据库名称,以数据库名称为唯一编号
            if (isset($this['db']) && isset($this['db']['table'])) {
                $this->_id = $this['db']['table'];
            } else {
                $this->_id = strtolower(get_class($this));
            }
        }
        return $this->_id;
    }

    /**
     * 设置元数据唯一编号
     *
     * @param string $id 编号
     * @return Qwin_Meta_Abstract 当前对象
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
        return $this;
    }

    /**
     *
     *
     * @param string $index 索引
     * @return mixed
     * @see Qwin_Meta_Abstract offsetGet
     */
    public function get($index)
    {
        return $this->offsetGet($index);
    }

    /**
     * 魔术方法,允许通过getXXX取值和setXXX设置值
     * 建议直接使用$this[$name],甚至是$this->offsetGet($name)和
     * $this->offsetSet($name)获得更好的效率
     *
     * @param string $name 方法名称
     * @param array $arguments 参数数组
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $lname = strtolower($name);
        $action = substr($lname, 0, 3);
        $element = substr($lname, 3);

        if ('get' == $action) {
            return $this->offsetGet($element);
        } elseif ('set' == $action) {
            if (1 === count($arguments)) {
                throw new Qwin_Meta_Common_Exception('You must specify the value to ' . $name);
            }
            return $this->offsetSet($element, $arguments[0]);
        }
        throw new Qwin_Meta_Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
    }

    /**
     *
     * @var array $_queryOptions 查询对象的选项
     *
     *      -- model                 模型的别名组成的数组
     *
     *      -- type                  模型的类型组成的数组
     *
     */
    /*protected $_queryOptions = array(
        'model'         => array(),
        'type'          => array(),
    );*/

    /**
     * 根据模块获取元数据对象
     *
     * @param string $module 模块标识
     * @return Qwin_Meta_Abstarct 元数据对象
     */
    public static function getByModule($module, $instanced = true)
    {
        if ($module instanceof Qwin_Module) {
            $class = $module->getClass();
        } else {
            $class = Qwin_Module::instance($module)->getClass();
        }
        $class .= '_Meta';
        return $instanced ? Qwin_Meta::getInstance()->get($class) : $class;
    }

    /**
     * 根据类型获取模型的元数据
     *
     * @param string $type 类型
     * @return array 由元数据组成的数组
     */
    public function getModelMetaByType($type = 'db')
    {
        if (empty($this['model'])) {
            return array();
        }
        $result = array();
        foreach ($this['model'] as $name => $model) {
            if ($model['enabled'] && $type == $model['type']) {
                if (!isset($model['meta'])) {
                    $model['meta'] = self::getByModule($model['module']);
                }
                $result[$name] = $model['meta'];
            }
        }
        return $result;
    }

    /**
     * 根据别名获取模型的元数据
     *
     * @param string $name 别名
     * @return Qwin_Meta_Abstract $meta 元数据
     */
    public function getModelMetaByAlias($name)
    {
        if (isset($this['model'][$name]['meta'])) {
            return $this['model'][$name]['meta'];
        }
        return $meta['model'][$name]['meta'] = self::getByAsc($meta['model'][$name]['asc']);
    }

    /**
     * 设置外键的值,保证数据之间能正确关联
     *
     * @param Qwin_Meta_Element_Model $modelList 模型配置元数据
     * @param array $data 经过转换的用户提交的数据
     * @return array 设置的后的值
     */
    public function setForeignKeyData($modelList, $data)
    {
        foreach ($modelList as $model) {
            if ('db' == $model['type']) {
                $data[$model['alias']][$model['foreign']] = $data[$model['local']];
            }
        }
        return $data;
    }

    /**
     * 删除主键的的值
     *
     * @param array $data
     * @param Qwin_Meta_Abstract $meta 元数据对象
     * @return array
     */
    public function unsetPrimaryKeyValue($data, Qwin_Meta_Abstract $meta)
    {
        $primaryKey = $meta['db']['primaryKey'];
        // 允许自定义主键的值
        /*if(isset($data[$primaryKey]))
        {
            $data[$primaryKey] = null;
            //unset($data[$primaryKey]);
        }*/
        foreach ($this->getModelMetaByType($meta, 'db') as $name => $relatedMeta) {
            $primaryKey = $relatedMeta['db']['primaryKey'];
            if (isset($data[$name][$primaryKey])) {
                $data[$name][$primaryKey] = null;
                //unset($data[$name][$primaryKey]);
            }
        }
        return $data;
    }
}
