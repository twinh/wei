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
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-22 23:58:30
 * @todo        区分theme和style
 */

class Trex_Member_Controller_Setting extends Trex_Controller
{
    /**
     * 用户中心
     */
    public function actionIndex()
    {
        /**
         * 设置视图
         */
        $theme = Qwin::run('-ini')->getConfig('interface.theme');
        $this->_view = array(
            'class' => 'Trex_View',
            'element' => array(
                array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/member/setting-center.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    /**
     * 切换语言
     */
    public function actionSwitchLanguage()
    {
        if(empty($_POST))
        {
            $urlLanguage = $this->request->g('language');
            $theme = Qwin::run('-ini')->getConfig('interface.theme');
            
            // 设置视图
            $this->_view = array(
                'class' => 'Trex_View',
                'element' => array(
                    array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/member/setting-language.php'),
                ),
                'data' => get_defined_vars(),
            );
        } else {
            $ses = Qwin::run('-ses');

            $member = $ses->get('member');
            $language = $ses->get('language');
            $language = Qwin::run('Qwin_Language')->toStandardStyle($language);
            
            $result = $this->metaHelper
                    ->getDoctrineQuery(array(
                        'namespace' => 'Trex',
                        'module' => 'Member',
                        'controller' => 'Member',
                    ))
                    ->where('id = ?', $member['id'])
                    ->fetchOne();
            $result['language'] = $language;
            $result->save();
            $url = Qwin::run('-url')->createUrl($this->_set);
            $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    /**
     * 切换风格
     */
    public function actionSwitchStyle()
    {
        if(empty($_POST))
        {
            $theme = $this->metaHelper
                ->getDoctrineQuery(array(
                    'namespace' => 'Trex',
                    'module' => 'Style',
                    'controller' => 'Theme',
                ))
                ->execute()
                ->toArray();
            $urlTheme = $this->request->g('style');
            $theme2 = Qwin::run('-ini')->getConfig('interface.theme');

            // 设置视图
            $this->_view = array(
                'class' => 'Trex_View',
                'element' => array(
                    array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme2 . '/element/member/setting-theme.php'),
                ),
                'data' => get_defined_vars(),
            );
        } else {
            $ses = Qwin::run('-ses');
            $member = $ses->get('member');
            $theme = $ses->get('style');
            
            $result = $this->metaHelper
                    ->getDoctrineQuery(array(
                        'namespace' => 'Trex',
                        'module' => 'Member',
                        'controller' => 'Member',
                    ))
                    ->where('id = ?', $member['id'])
                    ->fetchOne();
            $result['theme'] = $theme;
            $result->save();
            $url = Qwin::run('-url')->createUrl($this->_set);
            $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        } 
    }
}
