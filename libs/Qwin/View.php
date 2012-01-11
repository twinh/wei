<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * CrudController
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2010-08-06 19:25:40
 * @todo        错误与视图
 * @todo        rewrite
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
    protected $_elements;

    /**
     * 视图是否已展示
     * @var boolen
     */
    protected $_displayed = false;

    /**
     * 选项
     * @var array
     */
    public $options = array(
        'theme'         => 'cupertino',
        'charset'       => 'utf-8',
    );

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
        
        // 设置视图根目录为应用根目录
        $this->options['dirs'] = &$this->app->options['dirs'];
        
        // 获取主题
        $this->_getTheme();

        // 打开缓冲区
        ob_start();
    }
    
    public function getViewFile()
    {
        // 未定义视图元素,则查找该应用目录下的视图文件
        $module = $this->module()->toPath();
        $action = $this->action()->toString();
        foreach ($this->options['dirs'] as $dir) {
            $file = $dir . $module . 'views/' . $action . '.php';
            if (is_file($file)) {
                return $file;
            } else {
                $fileCache[] = $file;
            }
        }

        throw new Qwin_Exception('All view files not found: "' . implode(';', $fileCache) . '".');
    }
    
    /**
     * 展示视图
     */
    public function display($layout = null, array $data = null)
    {
        // 视图已输出
        if ($this->_displayed) {
            return false;
        }
        
        $this->trigger('beforeViewDisplay');
        
        // 部分视图常用变量
        $this->assign(array(
            'root'      => $this->config('resource') . 'view/apps/',
            'widget'    => $this,
            'lang'      => $this->lang,
            'minify'    => $this->minify,
            'jQuery'    => $this->jQuery,
            'config'    => $this->config(),
            'module'    => $this->module(),
            'action'    => $this->action(),
            'theme'     => $this->options['theme'],
        ));
 
        // 附加视图
        /*if (isset($layout)) {
            $this->_layout = array_shift($layout);
        }*/

        // 附加变量
        !empty($data) && $this->assign($data);
        extract($this->_data, EXTR_OVERWRITE);

        require $this->getViewFile();
        
        $this->trigger('afterViewDisplay');
        
        // TODO 是否应该通过钩子加载
        // 加载当前操作的样式和脚本
        $files = array();
        $action = $this->action();
        $moduleDir = $this->module()->toPath();
        foreach ($this->options['dirs'] as $dir) {
            $files[] = $dir . $moduleDir . 'views/' . $action . '.js';
            $files[] = $dir . $moduleDir . 'views/' . $action . '.css';
        }
        $this->minify->add($files);

        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        '' != $output && ob_end_clean();

        $url = Qwin::getInstance()->widget('url');
        $replace = Qwin_Util_Html::jsTag($url->url('util/minify', 'index', array('g' => $minify->pack('js')))) . PHP_EOL
                 . Qwin_Util_Html::cssLinkTag($url->url('util/minify', 'index', array('g' => $minify->pack('css')))) . PHP_EOL;

        // TODO appendAfter
        $output = Qwin_Util_String::replaceFirst('</head>', $replace . '</head>', $output);
        echo $output;
        unset($output);

        $this->setDisplayed();
        
        return $this;
    }

    /**
     * 展示视图
     *
     * @param string $layout 布局路径
     * @param array $data 附加数据
     * @todo 不只是输出文件,还有数据类型等等
     * @todo echo exit ?
     */
    public function call($layout = null, array $data = null)
    {
        if (is_string($layout)) {
            echo $layout;
            exit;
        } else {
            return $this->display($layout, $data);
        }
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
        $this->setElement('content', $this->getFile('views/info.php'));
        $this->minify->add($this->getFile('views/info.css'));

        $content = (array)$options['content'];
        
        // 开启错误调试且不是由异常发送过来的消息时,构造运行记录
        if ($this->config('debug') && !$options['exception']) {
            $error = $this->error;
            $traces = debug_backtrace();
            $content[] =  '<pre>'
                . $error->getTraceString($traces, 1)
                . $error->getFileCode($traces[1]['file'], $traces[1]['line'])
                . '</pre>';
        }
        
        $title = $this->lang[$options['title']];
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
     * 从视图目录获取文件路径
     * 
     * @param string $file 文件相对链接
     * @return string 
     * @todo cache
     */
    public function getFile($file)
    {
        if (file_exists($file)) {
            return $file;
        }
        foreach ($this->options['dirs'] as $dir) {
            if (is_file($file2 = $dir . $file)) {
                return $file2;
            }
        }
        $this->exception('File "%s" not found.', $file);
    }
    
    public function getUrlFile($file)
    {
        $file = realpath($this->getFile($file));
        return strtr(substr($file, strlen($_SERVER['DOCUMENT_ROOT'])), '\\', '/');
    }
    
    /**
     * 获取主题名称,主题为jQuery UI
     *
     * @see http://jqueryui.com/themeroller/
     * @return string
     * @todo 缓存主题加速查找
     */
    protected function _getTheme()
    {
        // 按优先级排列主题的数组
        $themes = array(
            (string)$this->get('theme'),
            $this->session['theme'],
            $this->options['theme'],
        );

        foreach ($themes as $value) {
            if ($value) {
                $theme = $value;
                break;
            }
        }
        
        // 在所有视图路径查找主题
        foreach ($this->options['dirs'] as $dir) {
            if (is_dir($dir . 'widgets/view/themes/' . $theme)) {
                $this->options['theme'] = $theme;
                $this->session['theme'] = $theme;
                return $this;
            }
        }

        $this->session['theme'] = $this->options['theme'];
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
