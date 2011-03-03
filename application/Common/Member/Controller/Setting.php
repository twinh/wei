<?php
/**
 * Setting
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
 * @package     Common
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-22 23:58:30
 * @todo        区分theme和style
 */

class Common_Member_Controller_Setting extends Common_Controller
{
    /**
     * 用户中心
     */
    public function actionIndex()
    {
        $meta = $this->_meta;
        $this->view->assign(get_defined_vars());
    }

    /**
     * 切换语言
     */
    public function actionSwitchLanguage()
    {
        if(empty($_POST))
        {
            $meta = $this->_meta;
            $langName = $this->request->getOption('lang');
            $urlLanguage = $this->request[$langName];
            
            $theme = $this->config['interface']['theme'];

            $this->view->assign(get_defined_vars());
        } else {
            $member = $ses->get('member');
            $language = $ses->get('language');
            $language = Qwin::call('Qwin_Language')->toStandardStyle($language);
            
            $result = $this->metaHelper
                    ->getQueryByAsc(array(
                        'namespace' => 'Common',
                        'module' => 'Member',
                        'controller' => 'Member',
                    ))
                    ->where('id = ?', $member['id'])
                    ->fetchOne();
            $result['language'] = $language;
            $result->save();
            $url = Qwin::call('-url')->url($this->_asc);
            $this->view->redirect($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    /**
     * 切换风格
     */
    public function actionSwitchStyle()
    {
        if(empty($_POST))
        {
            $model = Common_Model::getByAsc($this->_asc);
            $this->_model = Qwin::call($model);
            $styles = $this->_model->getStyles();
            $path = $this->_model->getPath();
            $meta = $this->_meta;

            $this->view->assign(get_defined_vars());
        } else {
            $ses = Qwin::call('-session');
            $member = $ses->get('member');
            $theme = $ses->get('style');
            
            $result = $this->metaHelper
                    ->getQueryByAsc(array(
                        'namespace' => 'Common',
                        'module' => 'Member',
                        'controller' => 'Member',
                    ))
                    ->where('id = ?', $member['id'])
                    ->fetchOne();
            $result['theme'] = $theme;
            $result->save();
            $url = Qwin::call('-url')->url($this->_asc);
            $this->view->redirect($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        } 
    }
}
