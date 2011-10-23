<?php
/**
 * Meta
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @package     Widget
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-28 17:26:04
 */

class Meta_Widget
{
    /**
     * 初始化对象
     *
     * @param array $input 默认数组
     */
    public function __construct($input = array())
    {
        parent::__construct($input);
        $class = get_class($this);
        $this['module'] = Qwin_Module::instance(substr($class, 0, strrpos($class, '_')));
    }

     /**
     * 在列表操作下,设置记录添加时间的格式为年月日
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string Y-m-d格式的日期
     */
    public function sanitiseListDateCreated($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * 在列表操作下,设置记录修改时间的格式为年月日
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string Y-m-d格式的日期
     */
    public function sanitiseListDateModified($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * 在列表操作下,为操作域设置按钮
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     * @todo 简化,重利用,是否需要用微件的形式
     * @todo widget
     */
    public function sanitiseListOperation($value, $name, $data, $dataCopy)
    {
        $id = $this['db']['id'];
        $url = Qwin::widget('url');
        $lang = Qwin::widget('lang');
        $module = $this->getModule();
        if (!isset($this->controller)) {
            // TODO　不重复加载
            $this->controller = Controller_Widget::getByModule($module->getUrl());
            $this->unableAction = $this->controller->getForbiddenActions();
        }
        // 不为禁用的行为设置链接
        $operation = array();
        if (!in_array('edit', $this->unableAction)) {
            $operation['edit'] = array(
                'url'   => $url->url($module->getUrl(), 'edit', array($id => $dataCopy[$id])),
                'title' => $lang->t('ACT_EDIT'),
                'icon'  => 'ui-icon-tag',
            );
        }
        if (!in_array('view', $this->unableAction)) {
            $operation['view'] = array(
                'url'   => $url->url($module->getUrl(), 'view', array($id => $dataCopy[$id])),
                'title' => $lang->t('ACT_VIEW'),
                'icon'  => 'ui-icon-lightbulb',
            );
        }
        /*if (!in_array('add', $this->unableAction)) {
            $operation['add'] = array(
                'url'   => $url->url($asc, array('action' => 'Add', $id => $dataCopy[$id])),
                'title' => $lang->t('ACT_COPY'),
                'icon'  => 'ui-icon-transferthick-e-w',
            );
        }*/
        if (!in_array('delete', $this->unableAction)) {
            if (!isset($this->page['useTrash'])) {
                $icon = 'ui-icon-close';
                $jsLang = 'MSG_CONFIRM_TO_DELETE';
            } else {
                $icon = 'ui-icon-trash';
                $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
            }
            $operation['delete'] = array(
                'url'   => 'javascript:if(confirm(qwin.lang.' . $jsLang . ')){window.location=\'' . $url->url($module->getUrl(), 'delete', array($id => $dataCopy[$id])) . '\';}',
                'title' => $lang->t('ACT_DELETE'),
                'icon'  => $icon,
            );
        }
        if (5 != func_num_args()) {
            $data = '';
            foreach ($operation as $row) {
                // TODO　如何结合button liveQuery ?
                $data .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="'
                      . $row['icon'] . '" href="' . $row['url'] . '"><span class="ui-icon ' . $row['icon']
                      .  '">' . $row['icon'] . '</span></a>';
                //$data .= '<a class="qw-anchor" href="' . $row['url'] . '" data="{icons:primary:\'' . $row['icon'] . '\'}">' . $row['title'] . '</a>';
            }
            return $data;
        } else {
            return $operation;
        }
    }

    /**
     * 在列表操作下,初始化排序域的值,依次按5递增
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return int 当前域的新值
     */
    public function sanitiseAddOrder($value, $name, $data, $dataCopy)
    {
        return 50;
        $query = $this->getQuery($this);
        $result = $query
            ->select($this->db['primaryKey'] . ', order')
            ->orderBy('order DESC')
            ->fetchOne();
        if(false != $result)
        {
            return $result['order'] + 5;
        }
        return 0;
    }

    /**
     * 在入库操作下,转换编号
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbId($value, $name, $data, $dataCopy)
    {
        if (empty($value)) {
            $value = Qwin_Util_String::uuid();
        }
        return $value;
    }

    /**
     * 在入库操作下,转换创建时间
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbDateCreated($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    /**
     * 在入库操作下,转换修改时间
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbDateModified()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    public function sanitiseDbCreatedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::call('Qwin_Session')->get('member');
        return $member['id'];
    }

    public function sanitiseDbModifiedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::call('Qwin_Session')->get('member');
        return $member['id'];
    }

    public function sanitiseDbIsDeleted($value, $name, $data, $dataCopy)
    {
        return 0;
    }

    public function sanitiseEditAssignTo($value, $name, $data, $dataCopy)
    {
        $data = Com_Meta::getQueryByModule('com/member')
            ->select('username')
            ->where('id = ?', $value)
            ->fetchOne();
        $this['field'][$name]['form']['_value2'] = $data['username'];
        return $value;
    }

    public function setLink($value, $name, $data, $dataCopy, $meta, $action)
    {
        if (isset($meta['fields'][$name]['_link']) && true == $meta['fields'][$name]['_link']) {
            $url = Qwin::widget('url');
            $name = str_replace(':', '\:', $name);
            if (null === $dataCopy[$name]) {
                $dataCopy[$name] = 'null';
            } else {
                $dataCopy[$name] = str_replace(':', '\:', $dataCopy[$name]);
            }
            $value = '<a href="' . $url->url($meta->getParent()->get('module')->getUrl(), 'index', array('search' => $name . ':' . $dataCopy[$name])) . '">' . $data[$name] . '</a>';
        }
        return $value;
    }
    
    public function sanitiseList()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'setLink'), $args);
    }
}
