<?php
/**
 * View
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
 * @package     Qwin
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-06 19:25:40
 * @todo        错误与视图
 */

class Qwin_View extends Qwin_Widget implements ArrayAccess
{
    /**
     * 语言转换数据
     * @var array
     */
    protected $_data = array();
    
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
     * 默认选项
     * @var array
     */
    public $options = array(
        'paths'         => array(),
        'style'         => 'cupertino',
        'theme'         => 'default',
        'charset'       => 'utf-8',
    );
    
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
        'exception' => null,
    );

    /**
     * 初始化类
     *
     * @param array $input 数据
     */
    public function __construct($source = null)
    {
        parent::__construct($source);
        $options = &$this->options;
        /*
        

        // 使视图一致 TODO 更合适的位置
        $request = Qwin::call('-request');
        if ($request['view']) {
            $this->_url->setOption('basicParams', array(
                'view' => $request['view'],
            ));
        }
        */
        // 设置默认的目录
        !is_array($options['paths']) && $options['paths'] = (array)$options['paths'];
        if (empty($options['paths'])) {
            $options['paths'][] = dirname(dirname(dirname(__FILE__))) . '/view/';
        }

        // 打开缓冲区
        ob_start();
    }

    /**
     * 输出视图
     * 输出视图的情况:显示数据,信息提示,跳转.
     * 当请求是Ajax时,返回content或特定视图,
     * 当请求是Json时,应该返回纯Json数据,以此类推.
     *
     * @param string $layout 布局路径
     * @param array $data 数据
     * @return View_Widget 当前对象
     */
    public function call($layout = null, array $data = null)
    {
        // 部分视图常用变量
        $this->assign(array(
            'root'      => $this->config('resource') . 'view/' . $this->options['theme'] . '/',
            'widget'    => $this,
            'lang'      => $this->lang,
            'minify'    => $this->minify,
            'jQuery'    => $this->jQuery,
            'config'    => $this->config(),
            'module'    => $this->config('module'),
            'action'    => $this->config('action'),
            'theme'     => $this->options['theme'],
            'style'     => $this->style,
        ));
        
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
        extract($this->_data, EXTR_OVERWRITE);

        // 根据Url的请求加载不同的视图
        $view = $this->get('view-only');
        if (!$this->elementExists($view)) {
            $view = 'layout';
        }
        require $this->getElement($view);
        
        
        // TODO 是否应该通过钩子加载
        // 加载当前操作的样式和脚本
        $minify = $this->minify;
        $files = array();
        foreach ($this->options['paths'] as $path) {
            $files[] = $path . $this->options['theme'] . '/' . $this['module'] . '/' . $this['action'] . '.js';
            $files[] = $path . $this->options['theme'] . '/' . $this['module'] . '/' . $this['action'] . '.css';
        }
        $minify->add($files);

        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        $url = Qwin::widget('url');
        $replace = Qwin_Util_Html::jsTag($url->url('util/minify', 'index', array('g' => $minify->pack('js')))) . PHP_EOL
                 . Qwin_Util_Html::cssLinkTag($url->url('util/minify', 'index', array('g' => $minify->pack('css')))) . PHP_EOL;

        $output = Qwin_Util_String::replaceFirst($this->getPackerSign(), $replace, $output);
        echo $output;
        unset($output);

        $this->setDisplayed();
        
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
        //$this->setElement('layout', '<root>layout<suffix>');
        $this->setElement('content', $this->_path . 'view/info.php');
        $this->_widget->get('minify')->add($this->_path . 'view/style.css');

        $content = (array)$options['content'];
        
        // 开启错误调试且不是由异常发送过来的消息时,构造运行记录
        if (Qwin::config('debug') && !$options['exception']) {
            $error = $this->_widget->get('error');
            $traces = debug_backtrace();
            $content[] =  '<pre>'
                . $error->getTraceString($traces, 1)
                . $error->getFileCode($traces[1]['file'], $traces[1]['line'])
                . '</pre>';
        }
        
        $title = $this->_widget->get('lang')->t($options['title']);
        $url = $options['url'];
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
     * @return View_Widget 当前对象
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
            $this->_data = $name + $this->_data;
        } else {
            $this->_data[$name] = $value;
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
        $this->_widget = Qwin::call('-widget');
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
     * 设置一个视图元素
     *
     * @param string $name 视图元素的名称
     * @param string|mixed $element 视图元素的路径
     * @return Qwin_Application_View 当前对象
     */
    public function setElement($name, $element)
    {
        $this->_element[$name] = $element;
        return $this;
    }
    
    public function getElement($name)
    {
        if (isset($this->_element[$name])) {
            return $this->_element[$name];
        }
        
        // 在视图目录找出视图路径
        // 根路径 + 风格目录 [+模块目录]
        $module = $this->config('module');
        $action = $this->config('module');
        $fileCache = array();
        foreach ($this->options['paths'] as $path) {
            $file = $path . $this->options['theme'] . '/' . $module . '/' . $action . '-' . $name . '.php';
            if (is_file($file)) {
                return $file;
            }
            $fileCache[] = $file;
            
            $file = $path . $this->options['theme'] . '/' . $module . '/' . $name . '.php';
            if (is_file($file)) {
                return $file;
            }
            $fileCache[] = $file;
            
            $file = $path . $this->options['theme'] . '/' . $name . '.php';
            if (is_file($file)) {
                return $file;
            }
            $fileCache[] = $file;
        }
        
        throw new Qwin_Widget_Exception('All view files not found: "' . implode(';', $fileCache) . '".');
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
    
    /**
     * 检查索引是否存在
     *
     * @param string $offset 索引
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * 获取索引的数据
     *
     * @param string $offset 索引
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : $offset;
    }

    /**
     * 设置索引的值
     *
     * @param string $offset 索引
     * @param mixed $value 值
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    /**
     * 销毁一个索引
     *
     * @param string $offset 索引的名称
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }
}
