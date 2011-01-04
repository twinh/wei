<?php
/**
 * View
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
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-14 11:12:00
 * @todo        是否需要一种简洁的加载模式与之相辅相成
 *              例如 $this->setViewPath('resource-path/view/theme')
 *                       ->setTheme('default')
 *                       ->loadView('namespace-module-controller-action');
 */

class Common_View extends Qwin_Application_View
{
    public function __construct()
    {
        // 布局的选择次序为 自定义视图 > 行为级 > 控制器级 > 模块级 > 默认(命名空间级)
        $this->setLayout(array(
            '<resource><theme>/<namespace>/layout/<module>-<controller>-<action><suffix>',
            '<resource><theme>/<namespace>/layout/<module>-<controller><suffix>',
            '<resource><theme>/<namespace>/layout/<module><suffix>',
            '<resource><theme>/<namespace>/layout/default<suffix>',
        ));

        // 默认视图元素的选择次序为 自定义视图 > 当前行为视图 > 默认模块视图 > 默认视图
        $this->setElement('content', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action><suffix>',
            '<resource><theme>/<namespace>/element/<defaultModule>/<defaultController>/<action><suffix>',
            '<resource><theme>/<namespace>/element/default<suffix>',
        ));

        // 当前行为的左栏操作视图
        $this->setElement('sidebar', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action>-sidebar<suffix>',
            '<resource><theme>/<namespace>/element/<defaultModule>/<defaultController>/<defaultAction>-sidebar<suffix>',
        ));

        // 当前行为的页眉标题视图
        $this->setElement('header', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action>-header<suffix>',
            '<resource><theme>/<namespace>/element/<defaultModule>/<defaultController>/<defaultAction>-header<suffix>',
        ));

        // 获取配置
        $ini = Qwin::run('-ini');
        $this->_config = $ini->getConfig();
        $set = $ini->getSet();
        
        $packerPath = QWIN_ROOT_PATH . '/cache/packer';

        // 设置css打包
        $cssPacker = Qwin::run('Qwin_Packer_Css');
        $cssPacker->setCachePath($packerPath)
            ->setCacheAge($this->_config['expiredTime'])
            ->setPathCacheAge($this->_config['expiredTime']);

        // 设置js打包
        $jsPacker = Qwin::run('Qwin_Packer_Js');
        $jsPacker->setCachePath($packerPath)
            ->setCacheAge($this->_config['expiredTime'])
            ->setPathCacheAge($this->_config['expiredTime']);

        $this->_data['cssPacker'] = $cssPacker;
        $this->_data['jsPacker'] = $jsPacker;

        $jquery = Qwin::run('Qwin_Resource_JQuery');
        $this->_data['jquery'] = $jquery;

        $jquery->setTheme($this->getStyle());
        
        $this->_theme = Qwin::run('-ini')->getConfig('interface.theme');

        $tagList = array(
            'resource' => QWIN_RESOURCE_PATH . '/view/theme/',
            'suffix' => '.php',
            'theme' => $this->_theme,
            'style' => $this->getStyle(),
            'namespace' => $set['namespace'],
            'module' => $set['module'],
            'controller' => $set['controller'],
            'action' => $set['action'],
            'defaultModule' => 'Common',
            'defaultController' => 'Common',
            'defaultAction' => 'Common',
        );
        $this->setTagList($tagList);

        // 部分视图常用变量
        $this->_data['set'] = $set;
        $this->_data['theme'] = $this->_theme;
    }

    /**
     * 获取风格,风格为jQuery的主题
     *
     * @return string
     */
    public function getStyle()
    {
        if(isset($this->_style))
        {
            return $this->_style;
        }

        $session = Qwin::run('Qwin_Session');
        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::run('Qwin_Request')->g('style'),
            $session->get('style'),
            Qwin::run('-ini')->getConfig('interface.style'),
        );
        foreach($styleList as $val)
        {
            if(null != $val)
            {
                $style = $val;
                break;
            }
        }

        if(!file_exists(QWIN_RESOURCE_PATH . '/js/jquery/themes/' . $style))
        {
            $style = Qwin::run('-ini')->getConfig('interface.style');
        }
        $session->set('style', $style);
        return $this->_style = $style;
    }

    public function display()
    {
        $this->preDisplay();
        extract($this->_data, EXTR_OVERWRITE);
        require $this->getLayout();
        $this->afterDisplay();
        return $this;
    }

    public function afterDisplay()
    {
        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        // TODO
        $search = array(
            '<!-- Qwin_Packer_Css -->',
            '<!-- Qwin_Packer_Js -->',
        );
        $replace = array(
            Qwin::run('Qwin_Packer_Css')->pack()->getHtmlTag(),
            Qwin::run('Qwin_Packer_Js')->pack()->getHtmlTag(),
        );
        $output = str_replace($search, $replace, $output);

        echo $output;
        unset($output);
    }
}
