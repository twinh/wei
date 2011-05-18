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
    
    public function actionFields()
    {
        $request = $this->_request;
        $meta = $this->getMeta();
        
        $from = $request->get('from');
        $from = Qwin_Util_Array::forceInArray($from, array('file', 'table'));
        $source = $request->get('source');
        
        // 读取元数据域配置文件
        if ('file' == $from) {
            
        } else {
            // 获取数据库字段配置
            $query = Com_Meta::getQueryByModule($this->_module);
            $manager = Doctrine_Manager::getInstance();
            $tableFormat = $manager->getAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT);
            $tableColumns = $query->getConnection()->import->listTableColumns(sprintf($tableFormat, $source));
            
//              [id] => Array
//        (
//            [name] => id
//            [type] => string
//            [alltypes] => Array
//                (
//                    [0] => string
//                )
//
//            [ntype] => char(36)
//            [length] => 36
//            [fixed] => 1
//            [unsigned] => 
//            [values] => Array
//                (
//                )
//
//            [primary] => 1
//            [default] => 
//            [notnull] => 1
//            [autoincrement] => 
//        )
            
//          'layout' => array(
//        array(
//            array('name'),
//            array('title'),
//            array('order'),
//            array('dbField'),
//            array('dbQuery'),
//            array('urlQuery'),
//            array('readonly'),
//            array('description'),
//        ),
//    ),

            // 将配置转换为元数据形式
            $formName = $source . 'form';
            $fields = array();
            $form = array(
                'fields' => array(),
                'layout' => array(),
            );
            foreach ($tableColumns as $name => $column) {
                $fields[$name] = array();
                $form['fields'][$name] = array();
                $form['layout'][$name] = array();
                
                foreach ($meta['form']['fields'] as $attrName => $attrForm) {
                    $form['layout'][$name][] = array($attrName);
                    $form['fields'][$attrName] = array(
                        'title' => $meta['fields'][$attrName]['title'],
                    );
                }
            }
            //qw_p($form);
            $meta->set('fields', $fields);
            $meta->set($formName, $form, 'form');
            //qw_p($meta[$formName]->getArrayCopy());
            
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