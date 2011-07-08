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
 * @package     Qwin
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-06 19:25:40
 * @todo        错误与视图
 */

class View_Widget extends ArrayObject implements Qwin_Widget_Interface
{
    /**
     * 视图元素数组
     * @var array
     */
    protected $_element;

    /**
     * 视图是否已展示
     * @var boolen
     */
    protected $_displayed = false;

    /**
     * 标签,表示变量标识符,用于布局和视图元素的路径中
     * @var array
     */
    protected $_tag = array(
        'theme' => 'default',
        'style' => 'default',
    );

    /**
     * 标签名称,用于替换标签,格式为 <标签的键名>
     * @var array
     * @todo 目前采用的是用空间换取时间的方式,是否有更好的方法加速标签替换
     */
    protected $_tagName = array(
        'theme' => '<theme>',
        'style' => '<style>'
    );

    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'style'         => 'cupertino',
        'theme'         => 'default',
        'charset'       => 'utf-8',
    );
    
    /**
     * 选项
     * @var array
     */
    protected $_options = array();
    
    /**
     * 打包的标记,用于合并js,css标签
     *
     * @var string
     */
    protected $_packSign = '<!-- qwin-packer-sign -->';

    /**
     * 信息提示视图的选项
     *
     * @var array
     */
    public $_infoOptions = array(
        'icon'      => 'info',
        'title'     => null,
        'url'       => null,
        'content'   => array(),
        'time'      => 3000,
        'customer'  => false,
    );

    /**
     * 初始化类
     *
     * @param array $input 数据
     */
    public function __construct(array $options = array())
    {
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
        $this->_options = $options + $this->_defaults;

        // 使视图一致 TODO 更合适的位置
        $request = Qwin::call('-request');
        if ($request['view']) {
            Qwin::widget('url')->setOption('basicParams', array(
                'view' => $request['view'],
            ));
        }
        
        // 打开缓冲区
        ob_start();
    }
        
    /**
     * 数据预处理,设置变量,路径标签,视图布局,元素等
     *
     * @return Com_View 当前对象
     */
    public function preDisplay()
    {
        // 获取配置
        $config = Qwin::config();
        $widget = Qwin::call('-widget');
        $style = $widget->get('Style');

        // 部分视图常用变量
        $this->assign(array(
            'widget'    => $widget,
            'lang'      => $widget->get('Lang'),
            'minify'    => $widget->get('Minify'),
            'jQuery'    => $widget->get('JQuery'),
            'config'    => $config,
            'module'    => Qwin::config('module'),
            'action'    => $config['action'],
            'theme'     => $this->_options['theme'],
            'style'     => $style,
        ));

        // 设置标签
        $this->setTag(array(
            'root'              => $config['resource'] . 'view/' . $this->_options['theme'] . '/',
            'suffix'            => '.php',
            'style'             => $style->getName(),
            'module'            => $this->module,
            'action'            => $config['action'],
        ));

        // 布局的选择次序为 自定义视图 > 行为级 > 控制器级 > 模块级 > 默认(命名空间级)
        if (!$this->elementExists('layout')) {
            $this->setElement('layout', array(
                '<root><module>/layout-<action><suffix>',
                '<root><module>/layout<suffix>',
                '<root><layout<suffix>'
            ));
        }
        
        // 默认视图元素的选择次序为 自定义视图 > 当前行为视图 > 默认模块视图 > 默认视图
        if (!$this->elementExists('content')) {
            $this->setElement('content', array(
                '<root><module>/content-<action><suffix>',
                '<root><module>/content<suffix>',
                '<root>content<suffix>'
            ));
        }
        
        return $this;
    }

    /**
     * 输出视图
     * 输出视图的情况:显示数据,信息提示,跳转.
     * 当请求是Ajax时,返回content或特定视图,
     * 当请求是Json时,应该返回纯Json数据,以此类推.
     *
     * @param string $layout 布局路径
     * @param array $data 数据
     * @return Com_View 当前对象
     */
    public function display($layout = null, array $data = null)
    {   
        $this->preDisplay();

        // 视图已输出
        if ($this->_displayed) {
            return false;
        }

        // 附加视图
        /*if (isset($layout)) {
            $this->_layout = array_shift($layout);
        }*/

        // 附加变量
        !empty($data) && $this->assign($data);
        extract($this->getArrayCopy(), EXTR_OVERWRITE);

        // 根据Url的请求加载不同的视图
        $request = Qwin::call('-request');
        $view = $request->get('view-only');
        if (!$this->elementExists($view)) {
            $view = 'layout';
        }
        require $this->getElement($view);
        $this->afterDisplay();
        $this->setDisplayed();
        
        return $this;
    }

    /**
     * 视图输出后的处理
     * 
     * @return View_Widget 当前对象
     */
    public function afterDisplay()
    {
        // TODO 是否应该通过钩子加载
        // 加载当前操作的样式和脚本
        $minify = $this->minify;
        $minify->addArray(array(
            $this->decodePath('<root><module>/<action>.js'),
            $this->decodePath('<root><module>/<action>.css'),
        ));
        //return $this;
        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        $url = Qwin::widget('url');
        $replace = Qwin_Util_Html::jsTag($url->url('util/minify', 'index', array('g' => $minify->pack('js')))) . PHP_EOL
                 . Qwin_Util_Html::cssLinkTag($url->url('util/minify', 'index', array('g' => $minify->pack('css'))));

        $output = Qwin_Util_String::replaceFirst($this->getPackerSign(), $replace, $output);
        echo $output;
        unset($output);
        return $this;
    }
    
    /**
     * 输出JSON数据
     * 
     * @param array $json JSON数组数据
     * @param bool $exit 是否退出 
     * @todo 是否应该直接输出
     */
    public function displayJson($json, $exit = true)
    {
        if (is_string($json)) {
            echo $json;
        } else {
            echo json_encode($json);
        }
        if ($exit) {
            exit;
        }
    }

    /**
     * 输出信息提示视图
     *
     * @param array $options 配置
     * @todo 如何不通过exit退出,又能防止其他视图类加载
     */
    public function displayInfo(array $options = array())
    {
        $options = $options + $this->_infoOptions;
        $this->setElement('layout', '<root>layout<suffix>');
        $this->setElement('content', Qwin::call('-widget')->getPath() . 'View/view/info.php');
        Qwin::widget('minify')->add(Qwin::call('-widget')->getPath() . 'View/view/style.css');

        $title = $options['title'];
        $url = $options['url'];
        $content = (array)$options['content'];
        $time = intval($options['time']);
        $meta['page']['title'] = 'MOD_INFO';
        $meta['page']['icon'] = $icon = $options['icon'];
        
        $this->assign(get_defined_vars());
        $this->display();
        exit;
    }

    /**
     * 输出通用类型信息提示视图
     *
     * @param string $info 信息
     * @param string $url 跳转地址
     * @param array|string $content 内容
     * @return Com_View 当前对象
     */
    public function info($info, $url = null, $content = array())
    {
        return $this->displayInfo(array(
            'title'     => $info,
            'url'       => $url,
            'content'   => $content,
        ));
    }

    /**
     * 输出成功类型的信息提示视图,如保存成功
     *
     * @param string $info 信息
     * @param string $url 跳转地址
     * @param array|string $content 内容
     * @return Com_View 当前对象
     */
    public function success($info, $url = null, $content = array())
    {
        return $this->displayInfo(array(
            'icon'      => 'tick',
            'title'     => $info,
            'url'       => $url,
            'content'   => $content,
        ));
    }

    /**
     * 输出警告类型的信息提示视图,用于一般性错误,如提交表单内容不正确
     *
     * @param string $info 信息
     * @param string $url 跳转地址
     * @param array|string $content 内容
     * @return Com_View 当前对象
     */
    public function alert($info, $url = null, $content = array())
    {
        return $this->displayInfo(array(
            'icon'      => 'warning',
            'title'     => $info,
            'url'       => $url,
            'content'   => $content,
        ));
    }

    /**
     * 输出错误类型的信息提示视图,用于严重性错误,如不可进行的操作
     *
     * @param string $info 信息
     * @param string $url 跳转地址
     * @param array|string $content 内容
     * @return Com_View 当前对象
     */
    public function error($info, $url = null, $content = array())
    {
        return $this->displayInfo(array(
            'icon'      => 'delete',
            'title'     => $info,
            'url'       => $url,
            'content'   => $content,
        ));
    }

    /**
     * 跳转
     *
     * @param string $url 地址
     * @return Com_View 当前对象
     */
    public function jump($url)
    {
        header('Location: ' . $url);
        exit;
        $this->setLayout('<resource><theme>/<defaultPackage>/layout/jump<suffix>');
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

    public function getRefererPage()
    {
        return urlencode(Qwin::call('-request')->server('HTTP_REFERER'));
    }

    /**
     * 设置变量
     *
     * @param string $name 变量名称
     * @param mixed $value 变量的值
     * @return object 当前对象
     */
    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            $this->exchangeArray(array_merge($this->getArrayCopy(), $name));
        } else {
            $this->offsetSet($name, $value);
        }
        return $this;
    }

    /**
     * 销毁一个变量
     *
     * @param string $name 变量名称
     * @return object 当前对象
     */
    public function clearAssign($name)
    {
        if (is_array($name)) {
            foreach($name as $key => $value) {
                 unset($this->_data[$key]);
            }
        } else {
            unset($this->_data[$name]);
        }
        return $this;
    }

    /**
     * 获取变量的值
     *
     * @param string $name
     * @return mixed 变量的值
     */
    public function getVariable($name = null)
    {
        if (null == $name) {
            return $this->_data;
        }
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * 设置一组视图元素
     *
     * @param array $list 视图元素组,键名为视图名称,值为视图的值
     * @return Qwin_Application_View 当前对象
     */
    public function setElementList(array $list)
    {
        foreach ($list as $key => $value) {
            !is_array($value) && $value = array($value);
            $this->_element[$key] = $value;
        }
        return $this;
    }

    /**
     * 设置一个视图元素
     *
     * @param string $name 视图元素的名称
     * @param string|mixed $element 视图元素的路径
     * @return Qwin_Application_View 当前对象
     */
    public function setElement($name, $element)
    {
        !is_array($element) && $element = array($element);
        $this->_element[$name] = $element;
        return $this;
    }

    /**
     * 获取未处理的视图元素
     *
     * @param string $name
     * @return string 视图元素
     */
    public function getRawElement($name)
    {
        return $this->_element[$name];
    }

    public function getElement($name)
    {
        if (!isset($this->_element[$name])) {
            throw new Qwin_Widget_Exception('Undefined view element name: ' . $name);
        }
        $pathCahce = array();
        foreach ($this->_element[$name] as $path) {
            $path = $this->decodePath($path);
            if (is_file($path)) {
                return $path;
            }
            $pathCahce[] = $path;
        }
        throw new Qwin_Widget_Exception('All view files not found: "' . implode(';', $pathCahce) . '".');
    }

    /**
     * 销毁视图元素
     *
     * @param string $name
     * @return object 当前对象
     */
    public function unsetElement($name)
    {
        if (isset($this->_element[$name])) {
            unset($this->_element[$name]);
        }
        return $this;
    }

    /**
     * 清空视图元素数组
     *
     * @return Qwin_Application_View 当前对象
     */
    public function clearElement()
    {
        $this->_element = array();
        return $this;
    }

    /**
     * 检查视图元素是否存在
     *
     * @param string $name 名称
     * @return bool
     */
    public function elementExists($name)
    {
        return isset($this->_element[$name]);
    }

    public function render($options = null)
    {
        return $this->display();
    }

    /**
     * 获取一个标签的值
     *
     * @param string $name 标签名称
     * @return string 标签的值
     */
    public function getTag($name)
    {
        return isset($this->_tag[$name]) ? $this->_tag[$name] : null;
    }

    /**
     * 设置一个标签的值
     *
     * @param string $name 标签名称
     * @param mixed $value 标签的值
     * @return Qwin_Application_View 当前对象
     */
    public function setTag($name, $value = null)
    {
        if (!is_array($name)) {
            $name = array($name => $value);
        }
        foreach ($name as $key => $value) {
            $this->_tag[$key] = strtolower($value);
            $this->_tagName[$key] = '<' . $key . '>';
        }
        return $this;
    }

    /**
     * 获取标签数组
     *
     * @return array  标签数组
     */
    public function getTagList()
    {
        return $this->_tag;
    }

    /**
     * 删除一个标签
     *
     * @param string $name 标签名称
     * @return Qwin_Application_View 当前对象
     */
    public function unsetTag($name)
    {
        if (isset($this->_tag[$name])) {
            unset($this->_tag[$name]);
            unset($this->_tagName[$name]);
        }
        return $this;
    }

    /**
     * 清空标签数组
     *
     * @return Qwin_Application_View 当前对象
     */
    public function clearTag()
    {
        $this->_tag = array();
        $this->_tagName = array();
        return $this;
    }

    /**
     * 根据标签设置将标签路径解码为真实路径
     *
     * @param string $path 路径
     * @return string 真实路径
     */
    public function decodePath($path)
    {
        return str_replace($this->_tagName, $this->_tag, $path);
    }

    /**
     * 设置视图已展示
     *
     * @return Qwin_Application_View 当前对象
     */
    public function setDisplayed()
    {
        $this->_displayed = true;
        return $this;
    }

    /**
     * 视图是否已展示
     *
     * @return boolen
     */
    public function isDisplayed()
    {
        return $this->_displayed;
    }
    
    public function getOption($name)
    {
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }
}
