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
 * @since       2011-04-05 06:14:49
 */

class Com_Member_My_Controller extends Com_Controller
{
    public function  __construct($config = array(), $module = null, $action = null) {
        parent::__construct($config, $module, $action);
        $this->_memberModule = new Qwin_Module('com/member');
    }

    public function actionIndex()
    {
        $meta = $this->getMetadata();
        $this->getView()->assign(get_defined_vars());
    }

    public function actionEdit()
    {
        $member = $this->getMember();
        if (!$this->_request->isPost()) {
            return Com_Widget::getByModule('Com', 'View')->execute(array(
                'module'    => $this->_memberModule,
                'id'        => $member['id'],
                'asAction'  => 'edit',
                'isView'    => false,
                'viewClass' => 'Com_View_Edit',
            ));
        } else {
            return Com_Widget::getByModule('Com', 'Edit')->execute(array(
                'module'    => $this->_memberModule,
                'data'      => $_POST,
                'url'       => urldecode($this->_request->post('_page')),
            ));
        }
    }

    public function actionView()
    {
        $member = $this->getMember();
        return Com_Widget::getByModule('Com', 'View')->execute(array(
            'module'    => $this->_memberModule,
            'id'        => $member['id']
        ));
    }

    public function actionStyle()
    {
        if (!$this->_request->isPost()) {
            $model = Com_Model::getByModule($this->_module);
            $styles = $model->getStyles();
            $path = $model->getPath();
            $meta = $this->getMetadata();
            
            $this->getView()->assign(get_defined_vars());
        } else {
            $session = $this->getSession();
            $member = $session->get('member');
            $style = $session->get('style');
            
            $result = Com_Metadata::getQueryByModule($this->_memberModule)
                ->update()
                ->set('theme', '?', $style)
                ->where('id = ?', $member['id'])
                ->execute();

            $url = $this->getUrl()->url($this->_module->toUrl(), 'index');
            $this->getView()->success(Qwin::call('-lang')->t('MSG_SUCCEEDED'), $url);
        } 
    }

    public function actionLanguage()
    {
        if (!$this->_request->isPost()) {
            $meta = $this->getMetadata();
            $urlLanguage = $this->_request->get('lang');

            $this->getView()->assign(get_defined_vars());
        } else {
            $session = $this->getSession();
            $member = $session->get('member');
            $lang = $session->get('lang');

            $result = Com_Metadata::getQueryByModule($this->_memberModule)
                ->update()
                ->set('language', '?', $lang)
                ->where('id = ?', $member['id'])
                ->execute();

            $url = $this->getUrl()->url($this->_module->toUrl(), 'index');
            $this->getView()->success(Qwin::call('-lang')->t('MSG_SUCCEEDED'), $url);
        }
    }
}
