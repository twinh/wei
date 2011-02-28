<?php
/**
 * Request
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-11 21:48:08
 * @todo        url空间 http://yourwebsite/?qw=[query-string]
 */

class Common_Request extends Qwin_Request
{
    /**
     * @var array $_option          数据请求的选项
     *
     *      键名选项
     *
     *      -- page                 列表页,当前页数
     *
     *      -- list                 列表页,列出的域
     *
     *      -- row                  列表页,每页显示数目
     *
     *      -- order                排序方式,用于多排序的情况,默认采用该方式
     *
     *      -- orderField           单域排序名称
     *
     *      -- orderType            单域排序类型
     *
     *      -- search               查找方式,用于多域查找,默认采用该方式
     *
     *      -- searchField          单域查找名称
     *
     *      -- searchValue          单域查找值
     *
     *      -- searchOper           单域查找操作符,如eq,lt,gt
     *
     *      -- lang                 语言名称,如Zh-cn,en-US
     *
     *      -- style                风格名称
     *
     *      -- theme                主题名称
     *
     *      -- ajax                 是否通过ajax请求
     *
     *      -- popup                是否以弹框形式请求
     *
     *      其他选项
     *
     *      -- maxRow               列表页最大显示行数
     */
    protected $_option = array(
        'id'            => 'id',
        'page'          => 'page',
        'list'          => 'list',
        'row'           => 'row',
        'order'         => 'order',
        'orderField'    => 'order-field',
        'orderType'     => 'order-type',
        'search'        => 'search',
        'searchField'   => 'search-field',
        'searchValue'   => 'search-value',
        'searchOper'    => 'search-oper',
        'lang'          => 'lang',

        'maxRow'        => 500,
    );

    /**
     * 获取选项的值
     *
     * @param string $name 选项名称
     * @return string 选项值
     */
    public function getOption($name)
    {
        return isset($this->_option[$name]) ? $this->_option[$name] : null;
    }

    /**
     * 设置选项的值
     *
     * @param string $name 选项名称
     * @param mixed $value 选项值
     * @return Common_Request 当前对象
     */
    public function setOption($name, $value)
    {
        $this->_option[$name] = $value;
        return $this;
    }
    
    /**
     * 获取请求中的排序配置,对字段域不做验证,字段域的验证应该是交给服务执行的
     *
     * @param string $order 排序域的名称
     * @param string $fieldName 字段域的名称
     * @param string $typeName 排序的名称
     * @return array 标准元数据的排序配置
     */
    public function getOrder($order = null, $fieldName = null, $typeName = null)
    {
        // 字符串类型排序,如 qwOrder=id:desc,title:asc
        null == $order && $order = $this->_option['order'];
        $orderValue = $this->get($order);
        if (null != $orderValue) {
            return $this->split($orderValue);
        }

        // 单域排序, 如qwOrderField=id&qwOrderType=desc
        null == $fieldName && $fieldName = $this->_option['orderField'];
        null == $typeName && $typeName = $this->_option['orderType'];

        $orderField = $this->get($fieldName);
        // 地址未设置排序
        if (null == $orderField) {
            return array();
        }

        $orderType = strtoupper($this->get($typeName));
        $typeOption = array('DESC', 'ASC');
        if (!in_array($orderType, $typeOption)) {
            $orderType = $typeOption[0];
        }
        return array(
            array($orderField, $orderType),
        );
    }

    /**
     * 获取请求中的查找配置,对字段域和操作符不做验证
     *
     * @param string $fieldName 字段域的名称
     * @param string $valueName 搜索值的名称(searchString)
     * @param string $operName 操作符的名称
     * @return array 标准元数据的搜索配置
     * @todo 高级复杂搜索配置
     */
    public function getWhere($search = null, $fieldName = null, $valueName = null, $operName = null)
    {
        // 字符串类型查找,如 qwSearch=title:me:eq,id:5:gt
        null == $search && $search = $this->_option['search'];
        $searchValue = $this->get($search);
        if (null != $searchValue) {
            return $this->split($searchValue);
        }

        $searchField = $this->get($fieldName);
        if (null == $searchField) {
            return array();
        }

        // 单域排序, 如qwSearchField=id&qwSearchValue=5&qwSearchOper=gt
        null == $fieldName && $fieldName = $this->_option['searchField'];
        null == $valueName && $valueName = $this->_option['searchValue'];
        null == $operName  && $operName  = $this->_option['searchOper'];
        
        return array(
            $this->get($fieldName),
            $this->get($valueName),
            $this->get($operName),
        );
    }

    /**
     * 获取请求中的偏移配置
     *
     * @param string $limitName 字段域的名称,应该与getUrlOffset中的rowName一致
     * @return int 标准元数据的限制配置
     * @todo 最大值允许配置
     */
    public function getLimit($limitName = null)
    {
        if (isset($this->_limit)) {
            return $this->_limit;
        }
        null == $limitName && $limitName = $this->_option['row'];
        $limit = intval($this->get($limitName));
        0 == $limit && $limit = 10;
        return $this->_limit = $this->_option['maxRow'] < $limit ? $this->_option['maxRow'] : $limit;
    }

    /**
     * 获取Url中的限制配置
     *
     * @param string $limitName 字段域的名称,应该与getUrlOffset中的rowName一致
     * @return int 标准元数据的限制配置
     * @todo 最大值允许配置
     */
    public function getOffset($pageName = null, $limitName = null)
    {
        null == $pageName && $pageName = $this->_option['page'];
        null == $limitName && $limitName = $this->_option['row'];
        
        $page = $this->getPage($pageName);
        $limit = $this->getLimit($limitName);
        return ($page - 1) * $limit;
    }

    public function getPage($pageName = null)
    {
        null == $pageName && $pageName = $this->_option['page'];
        $page = intval($this->get($pageName));
        return $page > 0 ? $page : 1;
    }

    /**
     * 获取请求中显示域的配置
     *
     * @param string $listName 键名
     * @param string $delimiter 分隔符
     * @return array
     */
    public function getListField($listName = null, $delimiter = ',')
    {
        if (null == $listName) {
            $listName = $this->_option['list'];
        }
        $list = $this->get($listName);
        if (null != $list) {
            $list = explode($delimiter, $list);
            foreach ($list as $key => $value) {
                $pos = strpos($value, '.');
                if (false !== $pos) {
                    $list[$key] = array(
                        substr($value, 0, $pos),
                        substr($value, $pos + 1),
                    );
                }
            }
        }
        return $list;
    }

    /**
     * 获取请求中元数据主键的值
     *
     * @uses Qwin_Application_Metadata
     * @param array $asc 应用结构配置
     * @return null|string 值
     */
    public function getPrimaryKeyValue(array $asc)
    {
        $meta = Common_Metadata::getByAsc($asc);
        return $this->get($meta['db']['primaryKey']);
    }

    public function getLang($name = null)
    {
        null == $name && $name = $this->_option['lang'];
        return $this[$name];
    }

    /**
     * 获取用户请求中的初始数据,主要用于添加操作
     *
     * @param string $search 请求的名称
     * @return array
     */
    public function getInitialData($search = null)
    {
        // 字符串类型查找,如 qwSearch=title:me:eq,id:5:gt
        null == $search && $search = $this->_option['search'];
        $searchValue = $this->get($search);
        if (null != $searchValue) {
            return $this->splitToInitalData($searchValue);
        }
        return array();
    }

    /**
     * 分割查询字符串为数组形式
     *
     * @param string $data 查询字符串
     * @return array 查询数组
     */
    public function split($data)
    {
        $data = preg_split('/(?<!\\\\)\,/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($data as &$row) {
            $row = strtr($row, array('\,' => ','));
            $row = preg_split('/(?<!\\\\)\:/', $row, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($row as &$element) {
                $element = strtr($element, array('\:' => ':'));
            }
        }
        return $data;
    }

    public function splitToInitalData($data)
    {
        $result = array();
        $data = preg_split('/(?<!\\\\)\,/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($data as &$row) {
            $row = strtr($row, array('\,' => ','));
            $row = preg_split('/(?<!\\\\)\:/', $row, -1, PREG_SPLIT_DELIM_CAPTURE);
            if (isset($row[0]) && isset($row[1])) {
                $result[$row[0]] = strtr($row[1], array('\:' => ':'));
            }
        }
        return $result;
    }

    /**
     * 是否请求以Json数据显示
     *
     * @return bool
     */
    public function isJson()
    {
        return (bool)$this->get('json');
    }

    /**
     * 是否请求以弹出窗口的形式显示
     *
     * @return bool
     */
    public function isPopup()
    {
        return (bool)$this->get('popup');
    }

    /**
     * 是否请求以弹出窗口的形式显示
     *
     * @return bool
     */
    public function isAjax()
    {
        return (bool)$this->get('ajax');
    }
}
