<?php
 /**
 * 后台用户
 *
 * 后台用户后台控制器
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-11-21 13:42 utf-8 中文
 * @since     2009-11-21 13:42 utf-8 中文
 */

class Admin_Controller_Setting extends Qwin_Miku_Controller
{
    private $separator = ':';
    private $field_prefix = 'field';

    function actionDefault()
    {
        if(!$_POST)
        {
            $db = Qwin_Class::run('-db');
            $table = $db->getList('SHOW TABLES');
            foreach($table as $key => $val)
            {
                $val = current($val);
                $resource[$val] = $val;

            }
            // 选择一个数据表
            $this->__meta = $this->setting->tableSetting();
            $this->__meta['field']['']['table']['form']['_resource'] = $resource;
            // 初始化视图变量数组
            $this->__view = array(
                'set' => $this->__meta,
                'data' => '',
                'http_referer' => urlencode(qw('-str')->set($_SERVER['HTTP_REFERER']))
            );

            // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
            $this->__cp_content = 'Resource/View/Element/AdminForm';
            $this->loadView(qw('-ini')->load('Resource/View/AdminControlPanel', false));
        } else {
            $gpc = Qwin_Class::run('-gpc');
            $url = url(
                array($this->__query['namespace'], $this->__query['controller'], 'Add'),
                array(
                    'table' => $gpc->p('table'),
                    'form_view_type' => 'tab',
                )
            );
            Qwin_Class::run('-url')->to($url);
        }
    }

    // 添加
    function actionAdd()
    {
        $db = Qwin_Class::run('-db');
        $gpc = Qwin_Class::run('-gpc');

        if(!$_POST)
        {
            // 获取所有数据表
            $table = $db->getList('SHOW TABLES');
            foreach($table as $key => $val)
            {
                $val = current($val);
                $table_arr[$val] = $val;

            }

            $gpc = Qwin_Class::run('-gpc');
            $table = $gpc->g('table');
            if(!in_array($table, $table_arr))
            {
                Qwin_Class::run('QMsg')->show('Table ' . $table . ' is not exists');
            }
            $sql = "DESCRIBE " . $table;
            $sql_data = $db->getList($sql);
            $field_set = array();
            // 各个字段排列的顺序
            $order = 0;
            // 各个字段顺序的初始值
            $order_val = 0;
            foreach($sql_data as $field)
            {
                // 构建字段
                $temp_arr = qw('-arr')->extendKey($this->__meta['field'], $field['Field'] . $this->separator);
                foreach($temp_arr as $key => &$val)
                {
                    // TODO
                    if('basic_order' == substr($key, -11))
                    {
                        $val['form']['_value'] = $order_val;
                        $order_val += 5;
                    } elseif('basic_title' == substr($key, -11)) {
                        $val['form']['_value'] = ucfirst(str_replace(array('_', '-'), ' ', $field['Field']));
                    } elseif('form_name' == substr($key, -9)) {
                        $val['form']['_value'] = $field['Field'];
                    }
                    $val['basic']['order'] = $order;
                    $val['basic']['group'] = $field['Field'];
                    $val['form']['name'] = $this->field_prefix . $this->separator .
                                           $field['Field'] . $this->separator . $val['form']['name'];
                    $order += 5;
                }
                $field_set += $temp_arr;
                // 更新主键
                if('PRI' == $field['Key'])
                {
                    $this->__meta['db']['primaryKey'] = $field['Field'];
                }
            }
            $this->__meta['field'] = $field_set + $this->setting->controllerSetting();

            //$this->__meta['field'] +=
            // TODO　三种模式　1.根据主键获取初始值(复制) 2.从url获取值 3. 获取模型默认值
            // 复制
            // 根据url参数中的值,获取对应的数据库资料
            $id = qw('-gpc')->g($this->__meta['db']['primaryKey']);
            qw('-qry')->setTable($this->__meta['db']['table']);
            $query_arr = array(
                'WHERE' => "`" . $this->__meta['db']['primaryKey'] . "` = '$id'",
            );
            $sql = qw('-qry')->getOne($query_arr);
            $data = qw('-db')->getOne($sql);

            // 检查数据是否存在
            if('' == $data[$this->__meta['db']['primaryKey']])
            {
                unset($data);
                // 从模型配置数组中取出表单初始值
                $data = $this->setting->getSettingValue($this->__meta['field'], array('form', '_value'));
                // 从url地址参数取出初始值,覆盖原值
                $data = qw('-url')->getInitalData($data);
            } else {
                unset($data[$this->__meta['db']['primaryKey']]);
            }

            // 根据配置和控制器中的对应方法转换数据
            $data = $this->setting->converSingleData($this->__meta['field'], $this->__query['action'], $data);

            $tip_data = $this->setting->getTipData($this->__meta['field']);

            // 获取 jQuery Validate 的验证规则
            $validator_rule = qw('Qwin_JQuery_Validator')->getRule($this->__meta['field']);

            // 排序
            $this->__meta['field'] = $this->setting->orderSettingArr($this->__meta['field']);
            // 分组
            $this->__meta['field'] = $this->setting->groupingSettingArr($this->__meta['field']);

            // 初始化视图变量数组
            $this->__view = array(
                'set' => $this->__meta,
                'data' => $data,
                'tip_data' => &$tip_data,
                'tip_name' => &$tip_name,
                'validator_rule' => &$validator_rule,
                'http_referer' => urlencode(qw('-str')->set($_SERVER['HTTP_REFERER'])),
                'form_view_type' => $gpc->g('form_view_type'),
            );

            // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
            $this->__cp_content = 'Resource/View/Element/AdminForm';
            $this->loadView(qw('-ini')->load('Resource/View/AdminControlPanel', false));
        } else {
            // TODO 安全问题
            $set = array();
            $form_type = $this->setting->getCommonClassList('form_type', 'rsc');
            foreach($_POST as $key => $val)
            {
                if(strstr($key, $this->separator))
                {
                    $key_arr = explode($this->separator, $key);
                    if($this->field_prefix == $key_arr[0])
                    {
                        $key_count = count($key_arr);
                        $eval = '$set["field"]';
                        for($i = 1; $i < $key_count; $i++)
                        {
                            $eval .= '["' . $key_arr[$i] . '"]';
                        }
                        // 转换
                        $val == 2001001 && $val = true;
                        if('_type' == $key_arr[$i-1])
                        {
                            $val = $form_type[$val];
                        }

                        if(!is_bool($val) && !is_numeric($val))
                        {
                            $val = '"' . $val . '"';
                        }
                        $eval .= '=' . $val . ';';
                        eval($eval);                        
                    }
                }
            }
            $set['field'] = Qwin_Class::run('Qwin_Helper_Array')->toPhpCode2($set['field'], '            ');
            $set['title'] = $gpc->p('title');
            $set['table'] = $gpc->p('table');
            $set['namespace'] = ucfirst($gpc->p('namespace'));
            $set['controller'] = ucfirst($gpc->p('controller'));
            $set['date'] = date('Y-m-d H:i:s', time());
            $set['file_encoding_sign'] = 'utf-8 中文';

            $file_data = file_get_contents(RESOURCE_PATH . '/php/Tpl/Setting.php');
            $ctrl_file_data = file_get_contents(RESOURCE_PATH . '/php/Tpl/Controller.php');
            foreach($set as $key => $val)
            {
                $file_data = str_replace('${' . $key . '}', $val, $file_data);
                $ctrl_file_data = str_replace('${' . $key . '}', $val, $ctrl_file_data);
            }
            $file_path = ROOT_PATH . '/App/' . $set['namespace'] . '/Setting/' . $set['controller'] . '.php';
            $ctrl_file_path = ROOT_PATH . '/App/' . $set['namespace'] . '/Controller/' . $set['controller'] . '.php';
            //if(!file_exists($file_path))
            //{
                file_put_contents($file_path, $file_data);
            //}
            //if(!file_exists($ctrl_file_path))
            //{
                file_put_contents($ctrl_file_path, $ctrl_file_data);
            //}
            Qwin_Class::run('-url')->to(url(array($this->__query['namespace'], $this->__query['controller'])));
        }
    }

    function converDbPassword($value)
    {
        if(strlen($value) == 32)
        {
            return $value;
        }
        return md5($value);
    }
}
