<?php
/**
 * Common Class
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
 * @package     Com
 * @subpackage  Option
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 15:21:00
 */

class Ide_Option_Meta extends Meta_Widget
{
    /*public function  setMeta()
    {
        $this->setCommonMeta()
             ->merge(array(
                'field' => array(
                    'language' => array(
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Ide_Option_Widget', 'get'),
                                'language',
                            ),
                        ),
                        'attr' => array(
                            'isLink' => 1,
                            'isList' => 1,
                        ),
                    ),
                    'sign' => array(
                        'attr' => array(
                            'isList' => 1,
                        ),
                        'validator' => array(
                            'rule' => array(
                                'required' => true,
                            ),
                        ),
                    ),
                    'code' => array(
                        'form' => array(
                            '_type' => 'custome',
                            '_widget' => array(
                                array(
                                    array('OptionEditor_Widget', 'render'),
                                ),
                            ),
                        ),
                        'attr' => array(
                            'isList' => 1,
                        ),
                        'db' => array(
                            'type' => null,
                        ),
                    ),
                ),
                'group' => array(

                ),
                'model' => array(

                ),
                'db' => array(
                    'table' => 'option',
                    'order' => array(
                        array('date_created', 'DESC'),
                    ),
                    'limit' => 10,
                ),
                'page' => array(
                    'title' => 'LBL_MODULE_OPTION',
                    'icon' => 'stop',
                ),
         ));
    }*/

    protected $_codeSample = array(
        'value' => null,
        'name' => null,
        'color' => null,
        'style' => null,
    );

    /**
     * 转换代码
     *
     * @param array $value 代码数组
     * @param string $name 域的名称
     * @param array $data 记录的数据
     * @param array $dataCopy 记录的原始数据
     * @return string 经过序列化的代码
     * @todo 样式,颜色的安全检查
     */
    public function sanitiseDbCode($value, $name, $data, $dataCopy)
    {
        !is_array($value) && $value = array();
        $return = array();
        foreach ($value as $row) {
            if (!isset($row['value']) || '' == $row['value']) {
                continue;
            } else {
                $return[$row['value']]['value']    = $row['value'];
                $return[$row['value']]['name']     = isset($row['name'])   ? $row['name']  : null;
                $return[$row['value']]['color']    = isset($row['color'])  ? $row['color'] : null;
                $return[$row['value']]['style']    = isset($row['name'])   ? $row['style'] : null;
            }
        }
        return serialize($return);
    }

    public function sanitiseEditCode($value, $name, $data, $dataCopy)
    {
        $value = @unserialize($value);
        !is_array($value) && $value = array(
            $this->_codeSample
        );
        return $value;
    }

    public function sanitiseAddCode($value, $name, $data, $dataCopy)
    {
        return array(
            $this->_codeSample
        );
    }

    public function sanitiseListCode($value, $name, $data, $dataCopy)
    {
        $value = @unserialize($value);
        !is_array($value) && $value = array();
        $nameList = array();
        foreach ($value as $row) {
            $nameList[] = $row['name'];
        }
        return implode(',', $nameList);
    }

    public function sanitiseViewCode($value, $name, $data, $dataCopy)
    {
        return $this->sanitiseListCode($value, $name, $data, $dataCopy);
    }
}
