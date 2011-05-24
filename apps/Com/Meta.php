<?php
/**
 * Meta
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @package     Com
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-28 17:26:04
 */

/**
 * @see Qwin_Application_Meta
 */
require_once 'Qwin/Meta/Abstract.php';

class Com_Meta extends Qwin_Meta_Abstract
{
    /**
     * 当前模块根路径
     * @var string
     */
    protected $_modulePath;

    /**
     * 初始化对象
     * 
     * @param array $input 默认数组
     */
    public function __construct($input = array())
    {
        parent::__construct($input);
        $class = get_class($this);
        $this->set('module', substr($class, 0, strrpos($class, '_')));
    }
    
    /**
     * 获取指定键名的元数据值，元数据不存在时将抛出异常
     *
     * @param string $index 键名
     * @return mixed
     */
    public function offsetGet($index)
    {
        if (parent::offsetExists($index)) {
            return parent::offsetGet($index);
        } elseif ($value = $this->_offsetGetByFile($index)) {
            return $value;
        }
        throw new Qwin_Meta_Exception('Undefined index "' . $index . '"');
    }

    /**
     * 根据键名加载元数据文件，元数据不存在时返回false
     *
     * @param string $index 键名
     * @return mixed
     */
    protected function _offsetGetByFile($index, $driver = null)
    {
        if (!$this->_modulePath) {
            $reflection = new ReflectionClass($this);
            $this->_modulePath = dirname($reflection->getFileName()) . '/';
        }
        $file = $this->_modulePath . 'meta/' .  $index . '.php';
        if (is_file($file)) {
            $this->set($index, require $file, $driver);
            return $this[$index];
        }
        return false;
    }

    /**
     * 获取指定键名的元数据值，元数据不存在时返回false
     *
     * @param string $index 键名
     * @return mixed
     */
    public function offsetLoad($index, $driver = null)
    {
        if (parent::offsetExists($index)) {
            return parent::offsetGet($index);
        }
        return $this->_offsetGetByFile($index, $driver);
    }
    
//    public function setAssignToMeta()
//    {
//        $this->merge('fields', array(
//            'assign_to' => array(
//                'order' => 1100,
//                'form' => array(
//                    '_type' => 'text',
//                    '_widget' => array(
//                        array(
//                            array('PopupGrid_Widget', 'render'),
//                            array(array(
//                                'title'  => 'LBL_MODULE_MEMBER',
//                                'module' => 'com/member',
//                                'list' => 'id,group_id,username,email',
//                                'fields' => array('username', 'id'),
//                            )),
//                        ),
//                    ),
//                ),
//                'attr' => array(
//                    'isLink' => 0,
//                    'isList' => 0,
//                ),
//                'validator' => array(
//                    'rule' => array(
//                        'required' => true,
//                    ),
//                ),
//            ),
//        ));
//        return $this;
//    }

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
        $url = Qwin::call('-url');
        $lang = Qwin::call('-widget')->get('Lang');
        $module = $this->getModule();
        if (!isset($this->controller)) {
            // TODO　不重复加载
            $this->controller = Com_Controller::getByModule($module->getString());
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
                'url'   => 'javascript:if(confirm(Qwin.Lang.' . $jsLang . ')){window.location=\'' . $url->url($module->getUrl(), 'delete', array($id => $dataCopy[$id])) . '\';}',
                'title' => $lang->t('ACT_DELETE'),
                'icon'  => $icon,
            );

        }
        if (5 != func_num_args()) {
            $data = '';
            foreach ($operation as $row) {
                $data .= Qwin_Util_JQuery::icon($row['url'], $row['title'], $row['icon']);
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
        if (null == $value) {
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

    public function setIsLink($value, $name, $data, $dataCopy, $action)
    {
        $url = Qwin::call('-url');
        $name = str_replace(':', '\:', $name);
        if (null === $dataCopy[$name]) {
            $dataCopy[$name] = 'NULL';
        } else {
            $dataCopy[$name] = str_replace(':', '\:', $dataCopy[$name]);
        }
        $value = '<a href="' . $url->url($this['module']->getUrl(), 'index', array('search' => $name . ':' . $dataCopy[$name])) . '">' . $data[$name] . '</a>';
        return $value;
    }
}
