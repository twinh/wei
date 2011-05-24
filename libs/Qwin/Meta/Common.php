<?php
/**
 * Driver
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-02-26 14:29:27
 * @method mixed get*(mixed $value) magic finders; @see __call()
 * @method mixed set*(mixed $value) magic finders; @see __call()
 */

class Qwin_Meta_Common extends ArrayObject implements Qwin_Meta_Interface
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array();

    /**
     * 选项
     * @var array
     */
    protected $_options = array();
    
    /**
     * 元数据父对象
     * 
     * @var Qwin_Meta_Abstract
     */
    protected $_parent;
    
    /**
     * @var array $_sanitiseOptions  数据处理的选项
     *
     *      -- view                 是否根据视图类关联模型的配置进行转换
     *                              提示,如果转换的数据作为表单的值显示,应该禁止改选项
     *
     *      -- null                 是否将NULL字符串转换为null类型
     *
     *      -- type                 是否进行强类型的转换,类型定义在['fieldName]['db']['type']
     *
     *      -- meta                 是否使用元数据的sanitise配置进行转换
     *
     *      -- sanitise             是否使用转换器进行转换
     *
     *      -- relatedMeta          是否转换关联的元数据
     */
    protected $_sanitiseOptions = array(
        'action'        => null,
        'view'          => true,
        'null'          => true,
        'type'          => true,
        'meta'          => true,
        'sanitise'      => false,
        'link'          => false,
        'notFilled'     => false,
        'relatedMeta'   => true,
    );

    /**
     * 初始化类
     *
     * @param array $array 数组
     */
    public function  __construct()
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
    }

    /**
     * 格式化数据,例如,设置默认数据,为未初始化的键名设置空值
     *
     * @param array $data 数据
     * @param array $options 选项
     * @return Qwin_Meta_Common 当前对象
     * @todo 是否一定要数组
     */
    public function merge($data, array $options = array())
    {
        $data = (array)$data + $this->_defaults;
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }

    /**
     * 以数组的形式格式化数据
     *
     * @return array 格式过的数据
     */
    protected function _mergeAsArray($data, array $options = array())
    {
        foreach ($data as $name => &$row) {
            $row = $this->_merge($name, $row, $options);
        }
        return $data;
    }

    /**
     * 格式化数据
     *
     * @return array 格式过的数据
     */
    protected function _merge($name, $data, array $options = array())
    {
        return array_merge($this->_defaults, $data);
    }

    /**
     * 获取选项
     *
     * @param string $name 配置名称
     * @return mixed
     */
    public function getOption($name = null)
    {
        if (null == $name) {
            return $this->_options;
        }
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }
    
    public function getDefault($name = null)
    {
        if (null == $name) {
            return $this->_defaults;
        }
        return isset($this->_defaults[$name]) ? $this->_defaults[$name] : null;
    }

    /**
     * 合并多维数组
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @todo 深度
     */
    protected function _multiArrayMerge($array1, $array2)
    {
        foreach($array2 as $key => $val) {
            if (is_array($val)) {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->_multiArrayMerge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }
    
    /**
     * 获取元数据数组中,键名为$key,值为$value的数据内容
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return array 数组
     * @todo 缓存
     */
    public function getBy($key, $value)
    {
        $result = array();
        foreach ($this as $name => $field) {
            if (isset($field[$key]) && $field[$key] === $value) {
                $result[$name] = true;
            }
        }
        return $result;
    }
    
    /**
     * 获取元数据$name键名中,键名为$key,值为$value的数据内容
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @param string $name 元数据键名
     * @return array 数组
     * @todo 缓存
     * @todo 高级筛选
     */
    public function getByField($key, $value, $name = 'fields')
    {
        if (!isset($this[$name])) {
            throw new Qwin_Meta_Common_Exception('Undefiend index "' . $name . '" in metadata ' . get_class($this));
        }
        $result = array();
        foreach ($this[$name] as $name => $field) {
            if (isset($field[$key]) && $field[$key] === $value) {
                $result[$name] = true;
            }
        }
        return $result;
    }
    
    /**
     * 设置父元数据对象,方便调用
     * 
     * @param Qwin_Meta_Abstract $meta 父元数据对象
     * @return Qwin_Meta_Common 当前对象
     */
    public function setParent(Qwin_Meta_Abstract $meta)
    {
        foreach ($meta as $element => $value) {
            if ($value === $this) {
                $this->_parent = $meta;
                return $this;
            }
        }
        throw new Qwin_Meta_Common_Exception('Sub meta not found.');
    }
    
    /**
     * 获取父元数据对象
     * 
     * @return Qwin_Meta_Abstract
     */
    public function getParent()
    {
        if (isset($this->_parent)) {
            return $this->_parent;
        }
        throw new Qwin_Meta_Common_Exception('Parent meta not defined.');
    }
    
    /**
     * 魔术方法,允许通过getXXX取值和setXXX设置值
     * 建议直接使用$this[$name],甚至是$this->offsetGet($name)和
     * $this->offsetSet($name)获得更好的效率
     * 
     * @param string $name 方法名称
     * @param array $arguments 参数数组
     * @return mixed 结果
     */
    public function __call($name, $arguments)
    {
        $lname = strtolower($name);
        $action = substr($lname, 0, 3);
        $element = substr($lname, 3);
        
        if ('get' == $action) {
            if ($this->offsetExists($element)) {
                return $this->offsetGet($element);
            }
        } elseif ('set' == $action) {
            if (0 === count($arguments)) {
                throw new Qwin_Meta_Common_Exception('You must specify the value to ' . $name);
            }
            return $this->offsetSet($element, $arguments[0]);
        }
        throw new Qwin_Meta_Common_Exception('Call to undefined method "' . get_class($this) .  '::' . $name . '()".');
    }

    /**
     * 处理数据
     * 
     * @param array $data 处理的数据
     * @param array $options 选项
     * @param array $dataCopy 完整数据备份
     * @return array 处理后的数据
     */
    public function sanitise(array $data, array $options = array(), array $dataCopy = array())
    {
        $options = $options + $this->_sanitiseOptions;
        empty($dataCopy) && $dataCopy = $data;
        
        // TODO 其他结构的转换
        if (!isset($this['fields'])) {
            throw new Qwin_Meta_Common('Metadata "' . get_class($this) . '" unsupport sanitisation.');
        }
        
        $meta = $this->getParent();

        // 加载流程处理对象
        if ($options['meta']) {
            $flow = Qwin::call('-flow');
        }
        if ($options['notFilled']) {
            $lang = Qwin::call('-widget')->get('Lang');
        }

        // 转换关联模块的显示域
        if ($options['view']) {
            foreach ((array)$meta->offsetLoad('meta') as $relatedMeta) {
                if ('view' != $relatedMeta['type']) {
                    continue;
                }
                foreach ($relatedMeta['fieldMap'] as $localField => $foreignField) {
                    if (isset($dataCopy[$relatedMeta['alias']][$foreignField])) {
                        $data[$localField] = $dataCopy[$relatedMeta['alias']][$foreignField];
                    }
                    // else throw exception ?
                    //!isset($data[$model['alias']][$foreignField]) && $data[$model['alias']][$foreignField] = '';
                }
            }
        }

        foreach ($data as $name => $value) {
//            if (!isset($this['fields'][$name])) {
//                continue;
//            }

//            if ('db' == $action) {
                // 空转换 如果不存在,则设为空
                if ('NULL' === $data[$name] || '' === $data[$name]) {
                    $data[$name] = null;
                }
//            } else {
//                if (null === $data[$name]) {
//                    $data[$name] = 'NULL';
//                }
//            }
            
            // 类型转换
            /*if ($options['type'] && $field['db']['type']) {
                if (null != $newData[$name]) {
                    if ('string' == $field['db']['type']) {
                        $newData[$name] = (string)$newData[$name];
                    } elseif ('integer' == $field['db']['type']) {
                        $newData[$name] = (int)$newData[$name];
                    } elseif ('float' == $field['db']['type']) {
                        $newData[$name] = (float)$newData[$name];
                    } elseif ('array' == $field['db']['type']) {
                        $newData[$name] = (array)$newData[$name];
                    }
                }
            }*/

            // 根据元数据中转换器的配置进行转换
            if ($options['meta']) {
                if (!isset($options['action'])) {
                    if (isset($this['fields'][$name]['sanitiser'])) {
                        $data[$name] = $flow->call(array($this['fields'][$name]['sanitiser']), Qwin_Flow::PARAM, $value);
                    }
                } else {
                    if (isset($this['fields'][$name]['sanitiser'][$action])) {
                        $data[$name] = $flow->call(array($this['fields'][$name]['sanitiser'][$action]), Qwin_Flow::PARAM, $value);
                    }
                }
            }

            // 使用转换器中的方法进行转换
            if ($options['sanitise']) {
                $method = str_replace(array('_', '-'), '', 'sanitise' . $options['sanitise'] . $name);
                if (method_exists($meta, $method)) {
                    $data[$name] = call_user_func_array(
                        array($meta, $method),
                        // $value or $data[$name] ?
                        array($value, $name, $data, $dataCopy)
                    );
                }
            }

            // 转换null值为未填写
            if ($options['notFilled'] && null === $data[$name]) {
                $data[$name] = $lang['LBL_NOT_FILLED'];
            }

            // 转换链接
            if ($options['link'] && isset($this['fields'][$name]['link']) && true == $this['fields'][$name]['link'] && method_exists($meta, 'setIsLink')) {
                $data[$name] = call_user_func_array(
                    array($meta, 'setIsLink'),
                    // $value or $data[$name] ?
                    array($value, $name, $data, $dataCopy, $options['action'])
                );
            }
        }

        // 对db类型的关联元数据进行转换
        if ($options['relatedMeta']) {
//            foreach ($meta->getModelMetaByType('db') as $name => $relatedMeta) {
//                !isset($data[$name]) && $data[$name] = array();
//                // 不继续转换关联元数据
//                $options['relatedMeta'] = false;
//                $data[$name] = $relatedMeta->sanitise($data[$name], $action, $options);
//            }
        }

        // 调用钩子方法
        //$this->postSanitise();

        return $data;
    }
    
//    /**
//     * 提供一个钩子方法,当数据处理开始时,调用此方法
//     */
//    public function preSanitise()
//    {
//    }
//
//    /**
//     * 提供一个钩子方法,当数据处理结束时,调用此方法
//     */
//    public function postSanitise()
//    {
//    }
}
