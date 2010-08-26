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
 * @since       2010-5-22 23:58:30
 */

class Trex_Member_Controller_Setting extends Qwin_Trex_Controller
{
    public function actionSwitchLang()
    {
        $ini = Qwin::run('-ini');
        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
        $this->__view_element = array(
            'content' => QWIN_RESOURCE_PATH . '/php/View/Element/MemberSettingLang.php',
        );
        $this->loadView($ini->load('Resource/View/Layout/DefaultControlPanel', false));
    }

    public function actionSwitchStyle()
    {
        $ini = Qwin::run('-ini');

        $set = array(
            'namespace' => 'Default',
            'module' => 'Style',
            'controller' => 'Theme',
        );
        $query = $this->meta->getQuery($set);
        $theme = $query->execute()->toArray();

        // 初始化视图变量数组
        $this->__view = array(
            'theme' => &$theme
        );

        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
        $this->__view_element = array(
            'content' => QWIN_RESOURCE_PATH . '/php/View/Element/MemberSettingStyle.php',
        );
        $this->loadView($ini->load('Resource/View/Layout/DefaultControlPanel', false));
    }

    public function actionApplyTheme()
    {
        $ini = Qwin::run('-ini');
        $ses = Qwin::run('-ses');
        $loginState = $ses->get('member');
        $style = Qwin::run('Qwin_Hepler_Util')->getStyle();
        $set = array(
            'namespace' => $this->_set['namespace'],
            'module' => $this->_set['module'],
            'controller' => 'Detail',
        );
        $metaName = $ini->getClassName('Metadata', $set);
        $modelName = $ini->getClassName('Model', $set);
        $this->__meta = Qwin::run($metaName)->defaultMetadata();
        $this->meta->metadataToModel($this->__meta, Qwin::run($modelName));

        $q = Doctrine_Query::create()
            ->update($modelName)
            ->set('theme_name', '?', $ses->get('style'))
            ->where('member_id = ?', $loginState['id'])
            ->execute();
        Qwin::run('-url')->to(url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'], 'SwitchStyle')));
    }

    public function actionApplyLang()
    {
        $ini = Qwin::run('-ini');
        $ses = Qwin::run('-ses');
        $loginState = $ses->get('member');
        $set = array(
            'namespace' => $this->_set['namespace'],
            'module' => $this->_set['module'],
            'controller' => 'Member',
        );
        $query = $this->meta->getQuery($set);
        $query = $query->where('id = ?', $loginState['id'])->fetchOne();
        $query['detail']['lang'] = $ses->get('lang');
        $query['detail']->save();
        Qwin::run('-url')->to(url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'], 'SwitchLang')));
    }
}
