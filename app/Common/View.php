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

class Common_View extends Qwin_App_View_Abstract
{
    public function __construct()
    {
        Qwin::set('-view', $this);
        $manager = Qwin::run('-manager');
        $widget = Qwin_Widget::getInstance();
        $widget->setRootPath(QWIN_RESOURCE_PATH . '/widget');

        // 加载jQuery微件
        $jQuery = $widget->get('jquery');

        // 布局的选择次序为 自定义视图 > 行为级 > 控制器级 > 模块级 > 默认(命名空间级)
        $this->setLayout(array(
            '<resource><theme>/<namespace>/layout/<module>-<controller>-<action><suffix>',
            '<resource><theme>/<namespace>/layout/<module>-<controller><suffix>',
            '<resource><theme>/<namespace>/layout/<module><suffix>',
            '<resource><theme>/<namespace>/layout/default<suffix>',
            '<resource><theme>/<defaultNamespace>/layout/default<suffix>',
        ));

        // 默认视图元素的选择次序为 自定义视图 > 当前行为视图 > 默认模块视图 > 默认视图
        $this->setElement('content', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>-<action><suffix>',
            '<resource><theme>/<defaultNamespace>/element/<defaultModule>/<defaultController>-<action><suffix>',
            '<resource><theme>/<namespace>/element/default<suffix>',
        ));

        // 当前行为的左栏操作视图
        $this->setElement('sidebar', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action>-sidebar<suffix>',
            '<resource><theme>/<defaultNamespace>/element/<defaultModule>/<defaultController>-<defaultAction>-sidebar<suffix>',
        ));

        // 当前行为的页眉标题视图
        $this->setElement('header', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action>-header<suffix>',
            '<resource><theme>/<namespace>/element/<defaultModule>/<defaultController>-<defaultAction>-header<suffix>',
        ));

        // 获取配置
        $config = Qwin::run('-config');

        $minify = $manager->getHelper('Minify', 'Common');
        $this->assign('minify', $minify);
        $this->assign('jQuery', $jQuery);
        
        $this->setTagList(array(
            'resource'          => QWIN_RESOURCE_PATH . '/view/theme/',
            'suffix'            => '.php',
            'theme'             => $config['interface']['theme'],
            'style'             => $this->getStyle(),
            'namespace'         => $config['asc']['namespace'],
            'module'            => $config['asc']['module'],
            'controller'        => $config['asc']['controller'],
            'action'            => $config['asc']['action'],
            'defaultNamespace'  => $config['defaultAsc']['namespace'],
            'defaultModule'     => $config['defaultAsc']['module'],
            'defaultController' => $config['defaultAsc']['controller'],
            'defaultAction'     => $config['defaultAsc']['action'],
        ));

        // 部分视图常用变量
        $this->_data['config'] = $config;
        $this->_data['asc'] = $config['asc'];
        $this->_data['theme'] = $config['interface']['theme'];
    }

    /**
     * 获取风格,风格为jQuery的主题
     *
     * @return string
     */
    public function getStyle()
    {
        if (isset($this->_style)) {
            return $this->_style;
        }
        if (!isset($this->config)) {
            $this->config = Qwin::run('-config');
        }

        $session = Qwin::run('Qwin_Session');
        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::run('-request')->g('style'),
            $session->get('style'),
            $this->config['interface']['style'],
        );
        foreach ($styleList as $val) {
            if (null != $val) {
                $style = $val;
                break;
            }
        }

        if (!file_exists(QWIN_RESOURCE_PATH . '/view/style/' . $style)) {
            $style = $this->config['interface']['style'];
        }
        $session->set('style', $style);
        return $this->_style = $style;
    }

    /**
     * 展示视图
     * 
     * @return Common_View
     */
    public function display($layout = null, array $data = null)
    {
        $this->preDisplay();
        if ($this->_displayed) {
            return false;
        }
        extract($this->_data, EXTR_OVERWRITE);

        $request = Qwin::run('#request');
        $isAjax = $request->g('qw-ajax');

        // TODO js重载等
        if (!$isAjax) {
            require $this->getLayout();
        } else {
            require $this->getElement('content');
        }
        
        $this->afterDisplay();
        return $this;
    }

    public function afterDisplay()
    {
        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        $url = Qwin::run('-url');
        $minify = $this->minify;
        $jsUrl = array('namespace' => 'Mini', 'g' => $minify->packJs());
        $cssUrl =  array('g' => $minify->packCss()) + $jsUrl;
        $replace = Qwin_Util_Html::jsTag($url->url($jsUrl))
                 . Qwin_Util_Html::cssLinkTag($url->url($cssUrl));

        // TODO
        $search = '<!-- qwin-packer-sign -->';
        $output = Qwin_Filter_String::replaceFirst($search, $replace, $output);
        echo $output;
        unset($output);
    }

    public function redirect($message, $method = null)
    {
        $this->assign('message', $message);
        $this->assign('method', $method);
        return $this;
    }

    public function jump($url)
    {
        $this->setLayout('<resource><theme>/<defaultNamespace>/layout/jump<suffix>');
        $this->assign('url', $url);
        return $this;
    }
}
