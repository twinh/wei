<?php
/**
 * Widget
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
 * @since       2011-6-29 16:55:02
 */

class Sanitiser_Widget extends Qwin_Widget_Abstract
{
    /**
     * @var array $_defaults        数据处理的选项
     * 
     *      -- null                 是否将'null'字符串转换为null类型,适用入库前转换
     * 
     *      -- nullTxt              是否用文字表示null值,如"(未填写)",适用显示数据类操作
     * 
     *      -- emptyTxt             是否用文字表示空值,如"(空)",适用显示数据类操作
     * 
     *      -- type                 是否强制类型转换
     * 
     *      -- sanitiser            是否使用元数据中转换器的配置进行转换
     * 
     *      -- sanitise             转换sanitise{Action}{Field}方法
     * 
     *      -- action               操作名称
     * 
     *      -- relation             
     * 
     *      -- relatedMeta          转换关联元数据的值
     */
    protected $_defaults = array(
        'null'          => false,
        'nullTxt'       => false, // NOT_FILLED_TXT
        'emptyTxt'      => false, // EMPTY_TXT
        'type'          => false,
        'sanitiser'     => false,
        'sanitise'      => false,
        'action'        => null,
        'relation'      => false,
        'relatedMeta'   => false,
    );
    
    public function render($options = null)
    {
        
    }
    
    /**
     * 处理数据
     * 
     * @param array $data 处理的数据
     * @param array $options 选项
     * @param array $dataCopy 完整数据备份
     * @return array 处理后的数据
     */
    public function sanitise(Qwin_Meta_Common $meta, array $data, array $options = array(), array $dataCopy = array())
    {
        $options = $options + $this->_options;
        empty($dataCopy) && $dataCopy = $data;
        
        // 存在字段配置的才允许转换
        if (!isset($meta['fields'])) {
            throw new Qwin_Meta_Common('Metadata "' . get_class($meta) . '" unsupport sanitisation.');
        }
        
        $parentMeta = $meta->getParent();

        // 加载流程处理对象
        if ($options['sanitiser']) {
            $flow = Qwin::call('-flow');
        }
        if ($options['nullTxt']) {
            $lang = Qwin::call('-widget')->get('lang');
        }
        
        $relationData = array();
        
        foreach ($data as $name => $value) {
            // 转换关联数据
            /*if ($options['relation'] && isset($meta['fields'][$name]['_relation'])) {
                $realation = &$meta['fields'][$name]['_relation'];
                if (!isset($relationData[$name])) {
                    $relationData[$name] = array();
                    $dbData = Meta_Widget::getByModule($realation['module'])->get('db')->getQuery()
                        ->select($realation['field'] . ', ' . $realation['display'])
                        ->execute();
                    foreach ($dbData as $row) {
                        $relationData[$name][$row[$realation['field']]] = $row[$realation['display']];
                    }
                }
                if (isset($relationData[$name][$value])) {
                    $data[$name] = $relationData[$name][$value];
                }
            }*/
            
            if ($options['null']) {
                if ('NULL' === $data[$name] || '' === $data[$name]) {
                    $data[$name] = null;
                }
            }
            
            // 类型转换
            /*if ($options['type'] && $field['db']['type']) {
                if (null != $newData[$name]) {
                    settype($newData[$name], $field['db']['type']);
                }
            }*/

            // 根据元数据中转换器的配置进行转换
            if ($options['sanitiser']) {
                if (isset($meta['fields'][$name]['_sanitiser'][$options['action']])) {
                    $data[$name] = $flow->call(array($meta['fields'][$name]['_sanitiser'][$options['action']]), Qwin_Flow::PARAM, $value);
                }
            }

            // 使用转换器中的方法进行转换
            if ($options['sanitise']) {
                $method = str_replace(array('_', '-'), '', 'sanitise' . $options['action'] . $name);
                if (method_exists($parentMeta, $method)) {
                    $data[$name] = call_user_func_array(
                        array($parentMeta, $method),
                        array($value, $name, $data, $dataCopy)
                    );
                }
            }

            // 转换null值为提示语"未填写"
            if ($options['nullTxt'] && is_null($data[$name])) {
                $data[$name] = $lang['NOT_FILLED_TXT'];
            }
            
            // 转换空值为提示语"空"
            if ($options['emptyTxt'] && empty($data[$name])) {
                $data[$name] = $lang['EMPTY_TXT'];
            }
            
            // 整体转换
            if ($options['sanitise']) {
                $method = 'sanitise' . $options['action'];
                if (method_exists($parentMeta, $method)) {
                    $data[$name] = call_user_func_array(
                        array($parentMeta, $method),
                        array($data[$name], $name, $data, $dataCopy, $meta, $options['action'])
                    );
                }
            }
        }

        // 对db类型的关联元数据进行转换
        //if ($options['relatedMeta']) {
//            foreach ($parentMeta->getModelMetaByType('db') as $name => $relatedMeta) {
//                !isset($data[$name]) && $data[$name] = array();
//                // 不继续转换关联元数据
//                $options['relatedMeta'] = false;
//                $data[$name] = $relatedMeta->sanitise($data[$name], $action, $options);
//            }
        //}
        return $data;
    }
}