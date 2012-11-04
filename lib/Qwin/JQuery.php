<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * JQuery
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class JQuery extends WidgetProvider
{
    public $options = array(
        'dir' => null,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->_dir = 'js/jquery';
    }

    /**
     * 获取jQuery文件目录
     *
     * @return string
     */
    public function getDir()
    {
        return $this->_dir;
    }

    public function __invoke()
    {
        return $this;
    }

    /**
     * 获取jQuery插件/UI等的文件路径
     *
     * @param  string $name 插件/UI等名称,多个以逗号隔开
     * @return array  文件数组
     */
    public function get($name)
    {
        $names = explode(',', $name);
        $files = array();

        foreach ($names as $name) {
            $name = trim($name);
            if ('jquery' == $name) {
                $files[] = $this->_dir . '/jquery.js';
            } else {
                $files[] = $this->_dir . '/' . $name . '/jquery.' . $name . '.js';
                $files[] = $this->_dir . '/' . $name . '/jquery.' . $name . '.css';
            }
        }

        return $files;
    }

    /**
     * 加载jQuery插件/UI等的文件路径
     *
     * @param  string $name 插件/UI等名称,多个以逗号隔开
     * @return string html代码
     * @todo 如果是磁盘路径,应该转换为Url
     */
    public function load($name)
    {
        $names = explode(',', $name);
        $html = '';

        foreach ($names as $name) {
            $name = trim($name);

            if ('jquery' == $name) {
                $file = $this->_dir . '/jquery.js';
                $html .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
                continue;
            }

            $file = $this->_dir . '/' . $name . '/jquery.' . $name . '.js';
            if (is_file($file)) {
                $html .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
            }

            $file = $this->_dir . '/' . $name . '/jquery.' . $name . '.css';
            if (is_file($file)) {
                $html .= '<link rel="stylesheet" type="text/css" media="all" href="' . $file . '" />' . "\n";
            }
        }

        return $html;
    }

    /**
     * 获取主题样式
     *
     * @param  string $name 主题名称
     * @return string
     */
    public function getTheme($name)
    {
        return $this->_dir . '/themes/' . $name . '/jquery.ui.theme.css';
    }
}
