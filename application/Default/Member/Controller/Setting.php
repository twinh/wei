<?php
/**
 * Setting
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-5-22 23:58:30 utf-8 中文
 * @since     2010-5-22 23:58:30 utf-8 中文
 */

class Default_Member_Controller_Setting extends Qwin_Trex_Controller
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
            'namespace' => $this->__query['namespace'],
            'module' => $this->__query['module'],
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
        Qwin::run('-url')->to(url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], 'SwitchStyle')));
    }

    public function actionApplyLang()
    {
        $ini = Qwin::run('-ini');
        $ses = Qwin::run('-ses');
        $loginState = $ses->get('member');
        $set = array(
            'namespace' => $this->__query['namespace'],
            'module' => $this->__query['module'],
            'controller' => 'Member',
        );
        $query = $this->meta->getQuery($set);
        $query = $query->where('id = ?', $loginState['id'])->fetchOne();
        $query['detail']['lang'] = $ses->get('lang');
        $query['detail']->save();
        Qwin::run('-url')->to(url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'], 'SwitchLang')));
    }
}
