<?php
/**
 * Metadata
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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 * @todo        关联元数据是否能实现功能性
 */

/**
 * @see Qwin_Metadata_Abstract
 */
require_once 'Qwin/Metadata/Abstract.php';

abstract class Qwin_Application_Metadata extends Qwin_Metadata_Abstract
{
    /**
     * @var array $_sanitiseOption  数据处理的选项
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
    protected $_sanitiseOption = array(
        'view'          => true,
        'null'          => true,
        'type'          => true,
        'meta'          => true,
        'sanitise'      => true,
        'link'          => false,
        'relatedMeta'   => true,
    );

    /**
     *
     * @var array $_queryOption 查询对象的选项
     *
     *      -- model                 模型的别名组成的数组
     *
     *      -- type                  模型的类型组成的数组
     *
     */
    /*protected $_queryOption = array(
        'model'         => array(),
        'type'          => array(),
    );*/

    /**
     * 根据模块获取元数据对象
     *
     * @param string $module 模块标识
     * @return Qwin_Metadata_Abstarct 元数据对象
     */
    public static function getByModule($module, $instanced = true)
    {
        $class = strtr($module, '/', '_') . '_Metadata';
        return $instanced ? Qwin_Metadata::getInstance()->get($class, $module) : $class;
    }

    /**
     *
     * @param array $asc
     * @return <type> 
     */
    public function getPrimaryKeyName(array $asc)
    {
        $metadataName = self::getByAsc($asc);
        if (!class_exists($metadataName)) {
            return null;
        }
        $meta = $this->_manager->get($metadataName);
        return $meta['db']['primaryKey'];
    }

    /**
     * 处理数据
     *
     * @param array $data 处理的数据
     * @param array $action 处理的行为,如db,list,view
     * @param array $option 选项
     * @return array
     */
    public function sanitise($data, $action = 'db', array $option = array(), array $dataCopy = array())
    {
        $option = array_merge($this->_sanitiseOption, $option);
        empty($dataCopy) && $dataCopy = $data;
        $action = strtolower($action);

        // 调用钩子方法
        $this->preSanitise();

        // 加载流程处理对象
        if ($option['meta']) {
            $flow = Qwin::call('Qwin_Flow');
        }

        /*if ($option['view']) {
            foreach ($meta['model'] as $model) {
                if ('view' == $model['type']) {
                    foreach ($model['fieldMap'] as $localField => $foreignField) {
                        !isset($data[$model['alias']][$foreignField]) && $data[$model['alias']][$foreignField] = '';
                        $data[$localField] = $data[$model['alias']][$foreignField];
                    }
                }
            }
        }*/

        foreach ($data as $name => $value) {
            if (!isset($this['field'][$name])) {
                continue;
            }

            // 空转换 如果不存在,则设为空
            if ('NULL' === $data[$name] || '' === $data[$name]) {
                $data[$name] = null;
            }

            // 类型转换
            /*if ($option['type'] && $field['db']['type']) {
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
            if ($option['meta'] && isset($this['field'][$name]['sanitiser'][$action])) {
                $data[$name] = $flow->call(array($this['field'][$name]['sanitiser'][$action]), $value);
            }

            // 使用转换器中的方法进行转换
            if ($option['sanitise']) {
                $method = str_replace(array('_', '-'), '', 'sanitise' . $action . $name);
                if (method_exists($this, $method)) {
                    $data[$name] = call_user_func_array(
                        array($this, $method),
                        array($value, $name, $data, $dataCopy)
                    );
                }
            }

            // 转换链接
            if ($option['link'] && 1 == $this->field[$name]['attr']['isLink'] && method_exists($this, 'setIsLink')) {
                $data[$name] = call_user_func_array(
                    array($this, 'setIsLink'),
                    array($value, $name, $data, $dataCopy, $action)
                );
            }
        }

        // 对db类型的关联元数据进行转换
        if ($option['relatedMeta']) {
            foreach ($this->getModelMetadataByType('db') as $name => $relatedMeta) {
                !isset($data[$name]) && $data[$name] = array();
                // 不继续转换关联元数据
                $option['relatedMeta'] = false;
                $data[$name] = $relatedMeta->sanitise($data[$name], $action, $option);
            }
        }

        // 调用钩子方法
        $this->postSanitise();

        return $data;
    }

    /**
     * 根据类型获取模型的元数据
     *
     * @param string $type 类型
     * @return array 由元数据组成的数组
     */
    public function getModelMetadataByType($type = 'db')
    {
        if (empty($this['model'])) {
            return array();
        }
        $result = array();
        foreach ($this['model'] as $name => $model) {
            if ($model['enabled'] && $type == $model['type']) {
                if (!isset($model['metadata'])) {
                    $model['metadata'] = self::getByModule($model['module']);
                }
                $result[$name] = $model['metadata'];
            }
        }
        return $result;
    }

    /**
     * 根据别名获取模型的元数据
     *
     * @param string $name 别名
     * @return Qwin_Metadata_Abstract $meta 元数据
     */
    public function getModelMetadataByAlias($name)
    {
        if (isset($this['model'][$name]['metadata'])) {
            return $this['model'][$name]['metadata'];
        }
        return $meta['model'][$name]['metadata'] = self::getByAsc($meta['model'][$name]['asc']);
    }

    /**
     * 设置外键的值,保证数据之间能正确关联
     *
     * @param Qwin_Metadata_Element_Model $modelList 模型配置元数据
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
     * @param Qwin_Metadata_Abstract $meta 元数据对象
     * @return array
     */
    public function unsetPrimaryKeyValue($data, Qwin_Metadata_Abstract $meta)
    {
        $primaryKey = $meta['db']['primaryKey'];
        // 允许自定义主键的值
        /*if(isset($data[$primaryKey]))
        {
            $data[$primaryKey] = null;
            //unset($data[$primaryKey]);
        }*/
        foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
            $primaryKey = $relatedMeta['db']['primaryKey'];
            if (isset($data[$name][$primaryKey])) {
                $data[$name][$primaryKey] = null;
                //unset($data[$name][$primaryKey]);
            }
        }
        return $data;
    }

    /**
     * 提供一个钩子方法,当验证开始时,调用此方法
     */
    public function preValidate()
    {
    }

    /**
     * 提供一个钩子方法,当验证完毕时,调用此方法
     */
    public function postValidate()
    {
    }

    /**
     * 提供一个钩子方法,当数据处理开始时,调用此方法
     */
    public function preSanitise()
    {
    }

    /**
     * 提供一个钩子方法,当数据处理结束时,调用此方法
     */
    public function postSanitise()
    {
    }
}
