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
    /**
     * 打包的标记,用于合并js,css标签
     *
     * @var string
     */
    protected $_packSign = '<!-- qwin-packer-sign -->';

    /**
     * 将当前视图对象加入注册器中
     */
    public function __construct()
    {
        parent::__construct();
        Qwin::get('-app')->setView($this);
        Qwin::set('-view', $this);
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
        $config = Qwin::config();

        $session = Qwin::call('-session');
        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::call('-request')->get('style'),
            $session['style'],
            $config['style'],
        );
        foreach ($styleList as $val) {
            if (null != $val) {
                $style = $val;
                break;
            }
        }

        if (!is_dir($config['resource'] . '/view/style/' . $style)) {
            $style = $config['style'];
        }
        $session['style'] = $style;
        return $this->_style = $style;
    }

    /**
     * 数据预处理,设置变量,路径标签,视图布局,元素等
     *
     * @return Common_View 当前对象
     */
    public function preDisplay()
    {
        // 获取配置
        $config = Qwin::config();

        // 部分视图常用变量
        $this->assign(array(
            'widget'    => Qwin::call('-widget'),
            'minify'    => Qwin::widget('minify'),
            'jQuery'    => Qwin::widget('jquery'),
            'config'    => $config,
            'asc'       => $config['asc'],
            'theme'     => $config['theme'],
        ));

        // 设置标签
        $this->setTag(array(
            'resource'          => $config['resource'] . 'view/theme/',
            'suffix'            => '.php',
            'theme'             => $config['theme'],
            'style'             => $this->getStyle(),
            'namespace'         => $config['asc']['namespace'],
            'module'            => $config['asc']['module'],
            'controller'        => $config['asc']['controller'],
            'action'            => $config['asc']['action'],
            'defaultNamespace'  => $config['defaultAsc']['namespace'],
            'defaultAction'     => $config['defaultAsc']['action'],
        ));
        
        // 布局的选择次序为 自定义视图 > 行为级 > 控制器级 > 模块级 > 默认(命名空间级)
        $this->setLayout(array(
            '<resource><theme>/<namespace>/layout/<module>-<controller>-<action><suffix>',
            '<resource><theme>/<namespace>/layout/<module>-<controller><suffix>',
            '<resource><theme>/<namespace>/layout/<module><suffix>',
            '<resource><theme>/<namespace>/layout/common<suffix>',
            '<resource><theme>/<defaultNamespace>/layout/common<suffix>',
        ));

        // 默认视图元素的选择次序为 自定义视图 > 当前行为视图 > 默认模块视图 > 默认视图
        $this->setElement('content', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>-<action><suffix>',
            '<resource><theme>/<defaultNamespace>/element/common/<action><suffix>',
            '<resource><theme>/<namespace>/element/common<suffix>',
        ));

        // 当前行为的左栏操作视图
        $this->setElement('sidebar', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action>-sidebar<suffix>',
            '<resource><theme>/<defaultNamespace>/element/common/sidebar<suffix>',
        ));

        // 当前行为的页眉标题视图
        $this->setElement('header', array(
            '<resource><theme>/<namespace>/element/<module>/<controller>/<action>-header<suffix>',
            '<resource><theme>/<namespace>/element/common/header<suffix>',
        ));

        return $this;
    }

    /**
     * 输出视图
     * 输出视图的情况:显示数据,信息提示,跳转.
     *
     * @param string $layout 布局路径
     * @param array $data 数据
     * @return Common_View 当前对象
     */
    public function display($layout = null, array $data = null)
    {
        $this->preDisplay();

        // 不再输出视图,一般在preDisplay中设置该参数
        if ($this->_displayed) {
            return false;
        }

        // 附加视图
        if (isset($layout)) {
            $this->_layout = array_shift($layout);
        }

        // 附加变量
        if (!empty($data)) {
            $this->assign($data);
        }
        extract($this->getArrayCopy(), EXTR_OVERWRITE);

        $request = Qwin::call('-request');

        // 加载布局
        if (!$request->isAjax()) {
            require $this->getLayout();
        } else {
            require $this->getElement('content');
        }
        
        $this->afterDisplay();
        $this->setDisplayed();
        
        return $this;
    }

    /**
     * 视图输出后的处理
     *
     * @return Common_View 当前对象
     */
    public function afterDisplay()
    {
        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        $url = Qwin::call('-url');
        $minify = $this->minify;
        $jsUrl = array('namespace' => 'Mini', 'g' => $minify->pack('js'));
        $cssUrl =  array('g' => $minify->pack('css')) + $jsUrl;
        $replace = Qwin_Util_Html::jsTag($url->url($jsUrl))
                 . Qwin_Util_Html::cssLinkTag($url->url($cssUrl));

        $output = Qwin_Util_String::replaceFirst($this->getPackerSign(), $replace, $output);
        echo $output;
        unset($output);
        return $this;
    }

    /**
     * 跳转
     *
     * @param string $message 提示信息
     * @param string $method 方式
     * @return Common_View 当前对象
     */
    public function redirect($message, $method = null)
    {
        $view = Qwin::call('Common_View_Redirect');
        $view->assign('message', $message);
        $view->assign('method', $method);
        $view->display();
        return $this;
    }

    /**
     * 跳转
     *
     * @param string $url 地址
     * @return Common_View 当前对象
     */
    public function jump($url)
    {
        $this->setLayout('<resource><theme>/<defaultNamespace>/layout/jump<suffix>');
        $this->assign('url', $url);
        return $this;
    }

    /**
     * 获取打包标记
     *
     * @return string
     */
    public function getPackerSign()
    {
        return $this->_packSign;
    }
}
