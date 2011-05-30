<?php

/**
 * Controller
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
 * @since       2011-05-17 11:25:29
 */

class Ide_Meta_Controller extends Com_Controller
{
    public function actionIndex()
    {
        
    }
    
    /**
     * 创建模块
     */
    public function actionCreateModule()
    {
        if (!$this->_request->isPost()) {
            $meta = $this->getMeta();
            $meta->offsetLoad('createmodule', 'form');
            
            $formWidget = $this->getWidget()->get('Form');
            $formOptions = array(
                'form' => $meta['createmodule'],
            );
            
            $this->getView()->assign(get_defined_vars());
        } else {
            
        }
    }
    
    public function actionFields()
    {
        $request = $this->_request;
        $meta = $this->getMeta();
        $lang = $this->getWidget()->call('Lang');
        
        // 模块
        $module2 = $request->get('module2');
        
        $from = $request->get('from');
        $from = Qwin_Util_Array::forceInArray($from, array('file', 'table'));
        
        //
        $source = $request->get('source');
        
        if ($request->isPost()) {
            qw_P($_POST);
        }
        
        // 读取元数据域配置文件
        if ('file' == $from) {
            
        } else {
            // 获取数据库字段配置
            $query = $this->getMeta()->get('db')->getQuery();
            $manager = Doctrine_Manager::getInstance();
            $tableFormat = $manager->getAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT);
            $tableColumns = $query->getConnection()->import->listTableColumns(sprintf($tableFormat, $source));
            
            // 构造表单元数据
            $formName = $source . 'form';
            $fields = array();
            $form = array(
                'fields' => array(),
                'layout' => array(),
            );
            foreach ($tableColumns as $name => $column) {
                $groupName = 'FLD_' . strtoupper($name);
                $fields[] = $name;
                //$form['fields'][$name] = array();
                $form['layout'][$groupName] = array();
                
                foreach ($meta['formsample'] as $attrName => $attrForm) {
                    $newName = $name . '[' . $attrName . ']';
                    $form['layout'][$groupName][] = array($newName);
                    $form['fields'][$newName] = array(
                        '_label' => 'FLD_' . strtoupper($attrName),
                        'name' => 'fields[' . $name . '][' . $attrName . ']',
                        'id' => $name . '_' . $attrName,
                    ) + $attrForm;
                    if ('title' == $attrName) {
                        $form['fields'][$newName]['class'] = 'qw-fields-' . $attrName;
                    } elseif ('name' == $attrName) {
                        $form['fields'][$newName]['_value'] = $name . '(' . $lang['LBL_READONLY'] . ')';
                        $form['fields'][$newName]['readonly'] = true;
                    }
                }
            }
            $meta->set($formName, $form, 'form');
        }
        $meta = $this->getMeta();
        $this->getView()->assign(get_defined_vars());
    }
    
    public function actionList()
    {
        
    }
    
    public function actionForm()
    {
        
    }
    
    public function actionDb()
    {
        
    }
    
    public function actionPage()
    {
        
    }
    
    public function actionMeta()
    {
        
    }
}