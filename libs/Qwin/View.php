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
 * @version     $Id: View.php 1249 2012-03-28 02:50:20Z itwinh@gmail.com $
 */

/**
 * View
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
    
    public function render($name = null, array $various = array())
    {
        
    }
    
    public function display($name = null, array $various = array())
    {
        
    }
    
    
    
    
    
    
    
    
    
    /**
     * 视图是否已展示
     *
     * @var boolen
     */
    protected $_displayed = false;

    /**
     * 选项
     *
     * @var array
     */
    public $options = array(
        'charset'       => 'utf-8',
    );

    /**
     * 初始化类
     *
     * @param mixed
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $options = &$this->options;

        // 设置视图根目录为应用根目录
        $this->options['dirs'] = &$this->app->options['dirs'];
    }

    /**
     * 展示视图
     *
     * @param string $layout 布局路径
     * @param array $data 附加数据
     * @todo 不只是输出文件,还有数据类型等等
     * @todo echo exit ?
     */
    public function __invoke($options = null)
    {
        // 判断视图是否已输入
        if ($this->_displayed) {
            return false;
        }
        $this->_displayed = true;

        if ($options['result']) {
            echo $options['result'];
            return $this;
        }

        // 部分视图常用变量
        $this->assign(array(
            'lang'      => $this->lang,
            'minify'    => $this->minify,
            'jQuery'    => $this->jQuery,
            'config'    => $this->config(),
            'module'    => $options['module'],
            'action'    => $options['action'],
        ));

        $this->_module = $options['module'];
        $this->_action = $options['action'];

        extract($this->_data, EXTR_OVERWRITE);

        ob_start();

        require $this->getViewFile($this->_module, $this->_action);

        // 获取缓冲数据,输出并清理
        $output = ob_get_contents();
        $output && ob_end_clean();

        // 加载当前操作的样式和脚本
        $files = array();
        $action = $this->action();
        $moduleDir = ucfirst($this->module());
        foreach ($this->options['dirs'] as $dir) {
            $files[] = $dir . '/' . $moduleDir . '/views/' . $action . '.js';
            $files[] = $dir . '/' . $moduleDir . '/views/' . $action . '.css';
        }
        $minify->add($files);

        $replace = '';
        if ($css = $minify->pack('css')) {
            $replace .= '<link rel="stylesheet" type="text/css" href="' . $this->url('minify', 'index', array('g' => $css)) . '"/>' . PHP_EOL;
        }
        if ($js = $minify->pack('js')) {
            $replace .= '<script type="text/javascript" src="' . $this->url('minify', 'index', array('g' => $js)) . '"></script>' . PHP_EOL;
        }
        if ($replace) {
            $output = $this->replaceFirst($output, '</head>', $replace . '</head>');
        }

        echo $output;
        unset($output);

        return $this;
    }
}
