<?php
/**
 * Seed
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
 * @since       2011-04-17 15:22:18
 */

class Ide_Seeder_Widget_Seed extends Qwin_Widget
{
    protected $_seedMeta = array(
        'field1' => array(
            'type' => 'string',
            'length' => '36',

            // 可选值
            'options' => array(
                '...', '...', '...',
            ),
            // 自定义回调结构取值
            'callback' => array(),
        ),
        'field2' => array(
            '...',
        ),
    );

    public $options = array(
        'module'    => null,
        'number'    => 1000,
        'display'   => true,
    );

    public function execute($options)
    {
        $options = $this->_options = $options + $this->_defaults;

        // 检查模块是否存在且合法
        $model = Com_Model::getByModule('ide/seeder');
        if (!($meta = $model->isModuleAvailable($options['module']))) {
            $view = $this->_View;
            $view->alert($this->_lang['MSG_MOD_UNAVAILABLE']);
        }

        // 获取数据库字段配置
        $query = Com_Meta::getQueryByModule($options['module']);
        $manager = Doctrine_Manager::getInstance();
        $tableFormat = $manager->getAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT);
        $tableColumns = $query->getConnection()->import->listTableColumns(sprintf($tableFormat, $meta['db']['table']));

        // 确定各域的值范围
        $seedMeta = array();
        foreach ($meta['field'] as $name => $field) {
            if (!$meta['field'][$name]['attr']['isDbField']) {
                continue;
            }

            if (!isset($tableColumns[$name])) {
                continue;
            }

            $seedMeta[$name] = array(
                'type' => $tableColumns[$name]['type'],
                'length' => $tableColumns[$name]['length'],
                'ntype' => $tableColumns[$name]['ntype'],
                'options' => array(),
            );

            $resource = $meta['field']->getResource($name);
            if (!empty($resource)) {
                $seedMeta[$name]['options'] = array_keys($resource);
            }
        }

        // 加载英语词库
        $this->_words = require Qwin::config('resource') . 'cache/english-word.php';
        
        // 根据元数据获取数据,并入库
        set_time_limit(0);
        $class = Com_Model::getByModule($options['module'], false);
        for ($i = 0; $i < $options['number']; $i++)
        {
            $data = $this->genDataFromMeta($seedMeta);
            $record = new $class;
            $record->fromArray($data);
            $record->save();
        }

        $view = $this->_View;
        $view->info($this->_lang['MSG_SUCCEEDED'], Qwin::call('-url')->url('ide/seeder'));
    }

    public function genDataFromMeta($seedMeta)
    {
        $data = array();
        foreach ($seedMeta as $field => $seed) {
            // 有可选项，直接从可选项取值
            if (!empty($seed['options'])) {
                $data[$field] = $seed['options'][array_rand($seed['options'])];
                continue;
            }

            // 整形，随机取整形的值
            // TODO Doctrine 对length的认定
            if ('integer' == $seed['type']) {
                $data[$field] = mt_rand(0, pow(10, $seed['length']));
                continue;
            }

            // timestamp ? datetime ?
            if ('timestamp' == $seed['type']) {
                $temp = mt_rand(-864000, 864000);
                $data[$field] = $_SERVER['REQUEST_TIME'] + $temp;
                continue;
            }

            //
            if ('string' == $seed['type']) {
                $length = mt_rand(0, $seed['length']);

                $temp = '';
                do {
                    $temp .= $this->_words[array_rand($this->_words)] . ' ';
                } while (strlen($temp) < $length);
                $data[$field] = substr($temp, 0, strrpos($temp, ' '));
                continue;
            }

            // TODO more types
            $data[$field] = null;
        }

        // todo
        if (array_key_exists('is_deleted', $data)) {
            $data['is_deleted'] = 0;
        }
        $data['id'] = Qwin_Util_String::uuid();
        return $data;
    }
}
