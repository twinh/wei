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
 * @todo        rewrite
 */
class Qwin_View extends Qwin_ArrayWidget
{
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
     * 初始化类
     *
     * @param mixed
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

    public function getViewFile($module = null, $action = null)
    {
        !$module && $module = $this->module();
        !$action && $action = $this->action();
        $module = (string)$module;
        $action = (string)$action;

        foreach ($this->options['dirs'] as $dir) {
            $file = $dir . '/' . $module . '/views/' . $action . '.php';
            if (is_file($file)) {
                return $file;
            } else {
                $fileCache[] = $file;
            }
        }

        throw new Qwin_Exception('All view files not found: "' . implode(';', $fileCache) . '".');
    }

    public function renderBy($module, $action = 'index')
    {
        // 部分视图常用变量
        $this->assign(array(
            'lang'      => $this->lang,
            'minify'    => $this->minify,
            'jQuery'    => $this->jQuery,
            'config'    => $this->config(),
            'module'    => $this->module(),
            'action'    => $this->action(),
            'theme'     => $this->options['theme'],
        ));

        // 附加变量
        !empty($data) && $this->assign($data);

        $this->_module = $module;
        $this->_action = $action;

        extract($this->_data, EXTR_OVERWRITE);

        require $this->getViewFile($this->_module, $this->_action);
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
        $moduleDir = ucfirst($this->module());
        foreach ($this->options['dirs'] as $dir) {
            $files[] = $dir . '/' . $moduleDir . '/views/' . $action . '.js';
            $files[] = $dir . '/' . $moduleDir . '/views/' . $action . '.css';
        }
        $this->minify->add($files);

        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        $output && ob_end_clean();

        // TODO maybe empty
        $url = Qwin::getInstance()->widget('url');
        $replace = Qwin_Util_Html::jsTag($url->url('minify', 'index', array('g' => $minify->pack('js')))) . PHP_EOL
                 . Qwin_Util_Html::cssLinkTag($url->url('minify', 'index', array('g' => $minify->pack('css')))) . PHP_EOL;

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
            if (is_file($file2 = $dir . '/' . $file)) {
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
            $this->cookie->get('theme'),
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
                $this->cookie->set('theme', $theme);
                return $this;
            }
        }

        $this->cookie->set('theme', $this->options['theme']);
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
}
