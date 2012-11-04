<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Minify
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        add group
 */
class Minify extends WidgetProvider
{
    /**
     * 存储文件的二维数组,键名为文件类型,如js,css,值为文件路径数组
     *
     * @var array
     */
    protected $_files = array();

    /**
     * 缓存对象
     *
     * @var Qwin_Storable
     */
    protected $_cache;

    /**
     * 测试输出的模板
     *
     * @var array
     */
    protected $_tpls = array(
        'js' => '<script type="text/javascript" src="%s"></script>',
        'css' => '<link rel="stylesheet" type="text/css" media="all" href="%s" />',
    );

    /**
     * 初始化
     */
    public function  __construct(array $options = array())
    {
        parent::__construct($options);

        // todo 缓存类型,参数
        $this->_cache = $this->fcache;

        $this->filter('response', array($this, 'replaceTags'));
    }

    public function __invoke($file)
    {
        return $this->add($file);
    }

    /**
     * 添加一个或多个文件
     *
     * @param  string      $file 文件路径
     * @return Qwin_Minify
     */
    public function add($file)
    {
        if (is_array($file)) {
            foreach ($file as $item) {
                $this->add($item);
            }
        } elseif (is_file($file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            $this->_files[$ext][] = $file;
        }

        return $this;
    }

    /**
     * 打包某一类后缀的文件
     *
     * @param  string $ext 后缀名
     * @return string 打包后的名称
     */
    public function pack($ext)
    {
        if (!isset($this->_files[$ext]) || empty($this->_files[$ext])) {
            return false;
        }

        $this->_files[$ext] = array_unique($this->_files[$ext]);

        $name = md5(implode('|', $this->_files[$ext]));

        if (!$this->_cache->get('minify_' . $name)) {
            $this->_cache->set('minify_' . $name, $this->_files[$ext]);
        }

        return $name;
    }

    /**
     * 根据名称获取文件数组
     *
     * @param  string      $name 名称
     * @return array|false
     */
    public function getFiles($name)
    {
        return $this->_cache->get('minify_' . $name);
    }

    /**
     * 打包一类文件
     *
     * @param  string $extension 后缀名
     * @return string 打包的名称
     */
    public function output($ext)
    {
        if (isset($tpl[$ext])) {
            if (isset($tpl[$ext])) {
                foreach ($this->_files[$ext] as $file) {
                    echo sprintf($tpl[$ext], $file);
                }
            } else {
                return false;
            }
        } else {
            return $this->exception('Unsupport output type "' . $ext . '"');
        }
    }

    public function replaceTags($output)
    {
        $replace = '';

        if ($css = $this->pack('css')) {
            $replace .= sprintf($this->_tpls['css'], $this->url('minify', 'index', array('id' => $css))) . PHP_EOL;
        }

        if ($js = $this->pack('js')) {
            $replace .= sprintf($this->_tpls['js'], $this->url('minify', 'index', array('id' => $js))) . PHP_EOL;
        }

        if ($replace) {
            $output = $this->replaceFirst($output, '</head>', $replace . '</head>');
        }

        return $output;
    }
}
