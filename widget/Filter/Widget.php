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
 * @since       2011-04-13 04:17:59 v0.7.9
 * @todo        ajax
 * @todo        int类型域的筛选
 * @todo        时间类型域的筛选
 * @todo        拖动某一格内容至筛选面板，筛选该值
 * @todo        当筛选值数量过多，换行时，换行位置不正确
 */

class Filter_Widget extends Qwin_Widget_Abstract
{
    protected $_defaults = array(
        'lang' => true,
    );

    protected $_formTypes = array(
        'select', 'radio', 'checkbox'
    );

    public function render($options)
    {
        if ('index' != $options['action']) {
            return false;
        }

        /* @var $request Qwin_Request */
        $request = Qwin::call('-request');
        if (!$request->get('filter')) {
            return false;
        }

        $lang = $this->_lang;
        $meta = $options['meta'];
        $url = Qwin::call('-url');
        $minify = $this->_widget->get('Minify');
        
        $search = $request->get('search');
        $searchData = Qwin_Util_String::splitQuery($search);
        $searchData = $this->sanitiseSearch($searchData);

        // 关闭的链接
        $returnUrl = $request->getGet();
        if (isset($returnUrl['filter'])) {
            unset($returnUrl['filter']);
        }
        $returnUrl = $url->build($returnUrl);

        // 撤销的链接
        if (!empty($searchData)) {
            $cancelUrl = $url->url($options['module']->toUrl(), $options['action'], array(
                'filter' => 1,
            ));
        }

        // 列出允许筛选的域
        $data = array();
        foreach ($meta['field'] as $name => $field) {
            if ($field['attr']['isDbField'] && in_array($field['form']['_type'], $this->_formTypes)) {
                $data[$name] = $meta['field']->getResource($name);

                // "全部"的选项
                $data[$name] = array(
                    null => array(
                        'name' => $lang['LBL_ALL'],
                        'value' => null,
                    ),
                    'NULL' => array(
                        'name' => $lang['LBL_NOT_FILLED'],
                        'value' => 'NULL',
                    ),
                ) + $data[$name];

                $urlData = $searchData;
                if (isset($urlData[$name])) {
                    unset($urlData[$name]);
                }

                // 构造Url
                foreach ($data[$name] as $key => $row) {
                    $data[$name][$key]['url'] = $url->url($options['module']->toUrl(), $options['action'], array(
                        'search' => $this->restoreSearch($urlData + array(
                            $name => $data[$name][$key]['value'],
                        )),
                        'filter' => 1,
                    ));
                }

                // 存在该域的查询，高亮该域
                if (isset($searchData[$name]) && isset($data[$name][$searchData[$name]])) {
                    $seletedKey = $searchData[$name];

                    // 重设“全部”的链接
                    reset($data[$name]);
                    $firstKey = key($data[$name]);
                    $data[$name][$firstKey]['url'] = $url->url($options['module']->toUrl(), $options['action'], array(
                        'search' => $this->restoreSearch($urlData),
                        'filter' => 1,
                    ));

                // 不存在该域的查询，高亮第一项，即"全部"的选项
                } else {
                    reset($data[$name]);
                    $seletedKey = key($data[$name]);
                }

                $data[$name][$seletedKey]['selected'] = true;
                $data[$name][$seletedKey]['url'] = 'javascript:;';
            }
        }

        $minify->add($this->_rootPath . 'view/style.css');
        require $this->_rootPath . 'view/default.php';
    }

    /**
     * 处理查询的数据，找出类型为“等于”的查询
     *
     * @param array $data 查询的数据
     * @return array
     */
    public function sanitiseSearch($data)
    {
        $result = array();
        foreach ($data as $row) {
            if (!isset($row[2]) || 'eq' == $row[2]) {
                $result[$row[0]] = $row[1];
            }
        }
        return $result;
    }

    public function restoreSearch($data) {
        $result = array();
        foreach ($data as $key => $value) {
            $result[] = $key . ':' . $value;
        }
        return implode(',', $result);
    }
}