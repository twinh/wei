<?php
/**
 * Tree
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @subpackage  Tree
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-26 22:19:56
 * @todo        根据分类找出其所有子类, 根据子类找出其父类
 */

class Qwin_Tree
{
    /**
     * 编号的键名
     * @var string
     */
    private $_id = 'id';

    /**
     * 父编号的键名
     * @var string
     */
    private $_parentId = 'parent_id';

    /**
     * 主值的键名,主值一般为分类名称
     * @var string
     */
    private $_name = 'name';

    /**
     * 顶级分类的的默认值
     * 一般数字的为0,UUID为NULL
     * @var mixed
     */
    private $_parentDefaultValue = 0;

    /**
     * 存放各个节点所在的层次,从0开始
     * @var array
     */
    private $_layer;

    /**
     * 存放各个节点子树的数组
     * @var array
     */
    private $_child;

    /**
     * 存放各个节点父树的数组
     * @var array
     */
    private $_parent;

    /**
     * 存放各个节点的数据
     * @var array
     */
    private $_data;

    /**
     * $_data存储的数据类型, ID/ARRAY
     * @var string
     * @todo 使用常量
     */
    private $_dataType = 'ID';


    /**
     * 储存最后节点标志的数组
     * @var array
     */
    private $_lastNode;

    /**
     * 是否已经获知最后的节点
     * @var <type>
     */
    private $_isSetLastNode = false;


    /**
     * 设置编号的值
     * @param string/int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * 设置父编号的值
     * @param string/int $parentId
     */
    public function setParentId($parentId)
    {
        $this->_parentId = $parentId;
        return $this;
    }

    /**
     * 设置主值名称
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function setDataType($type)
    {
        $this->_dataType = 'ID' == $type ? 'ID' : 'ARRAY';
        return $this;
    }

    /**
     * 设置顶级分类的的默认值
     * @param mixed $value
     */
    public function setParentDefaultValue($value)
    {
        $this->_parentDefaultValue = $value;
        return $this;
    }

    /**
     * 添加子节点
     * @param array $data
     */
    public function addNode($data)
    {
        if('ID' == $this->_dataType)
        {
            $this->_data[$data[$this->_id]] = $data[$this->_name];
        } else {
            $this->_data[$data[$this->_id]] = $data;
        }

        // 设置子树数组
        if(!isset( $this->_child[$data[$this->_id]]))
        {
            $this->_child[$data[$this->_id]] = array();
        }
        // 允许没有父分类,没有父分类则为一级分类
        !isset($data[$this->_parentId]) && $data[$this->_parentId] = null;
        $this->_child[$data[$this->_parentId]][] = $data[$this->_id];

        // 设置父树数组
        $this->_parent[$data[$this->_id]] = $data[$this->_parentId];
    }

    /**
     * 获取指定节点的主值
     * @param mixed $id
     * @return mixed 节点的值
     */
    public function getValue($id)
    {
        return $this->_data[$id];
    }

    /**
     * 获取父节点的键名
     * @param mixed $id
     * @return mixed 节点的值
     */
    public function getParent($id)
    {
        return $this->_parent[$id];
    }

    /**
     * 获取指定分类下的所有分类
     * @param array $list 存放分类的数组,按顺序存储,
     * @param mixed $id 分类的键名
     * @todo $id的值
     */
    public function getAllList(&$list, $id = 'DEFAULT_ID_VALUE')
    {
        if('DEFAULT_ID_VALUE' == $id)
        {
            $id = $this->_parentDefaultValue;
        }
        if(  !empty($this->_child[$id]))
        {
            foreach($this->_child[$id] as $child)
            {
                // 将该分类的子分类加入
                $list[] = $child;
                // 如果子分类还有还有子分类,继续加入
                $this->getAllList($list, $child);
            }
        }
    }

    /**
     * 设置节点所在的层次
     */
    public function setLayer($list = null)
    {
        if(null == $list)
        {
            $this->getAllList($list);
        }
        foreach($list as $id)
        {
            $parentId = $this->getParent($id);
            if(!isset($this->_layer[$parentId]))
            {
                $this->_layer[$id] = 0;
            } else {
                $this->_layer[$id] = $this->_layer[$parentId] + 1;
            }
        }
    }

    /**
     * 获取指定分类所在的层次
     * @param string $id
     * @param string $space
     * @return string/int
     */
    public function getLayer($id, $space = false)
    {
        if($space)
        {
            return str_repeat($space, $this->_layer[$id]);
        }
        return $this->_layer[$id];
    }

    public function setLastNode()
    {
        if($this->_isSetLastNode)
        {
            foreach($this->_child as $child)
            {
                $this->_lastNode[$id] = end($child);
            }
            $this->_isSetLastNode = false;
        }
    }

    /**
     * 根据树所在的层次,设置前缀,例如 ┗ ┃ ┣
     */
    public function setPrefix()
    {
        
    }
}
