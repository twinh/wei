<?php
/**
 * Asc
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-03-11 9:44:23
 */

class Qwin_Application_Asc
{
    /**
     * 默认应用结构配置
     * @var string
     */
    protected $_asc;

    /**
     * 默认应用结构配置信息
     * @var array
     */
    protected $_info;

    /**
     * 缓存,键名为Asc,值为解析后的信息
     * @var array
     */
    protected $_cache = array();

    /**
     * 配置
     * @var array
     */
    protected $_option = array(
        'rootModule'    => 'Common',
        'action'        => 'Index',
    );

    /**
     * 初始化
     *
     * @param string $asc 应用结构配置,可选
     */
    public function __construct($asc = null)
    {
        if (isset($asc)) {
            $this->setDefault($asc);
        }
    }

    /**
     * 设置默认应用结构配置
     *
     * @param string $asc 应用结构配置
     */
    public function setDefault($asc)
    {
        $this->_info = $this->_getInfo($asc);
        $this->_asc = $asc;
    }

    /**
     * 获取应用配置结构信息
     *
     * @param string $asc 应用配置结构
     * @return array 应用配置结构信息
     */
    public function getInfo($asc = null)
    {
        return isset($asc) ? $this->_getInfo($asc) : $this->_info;
    }

    /**
     * 解析应用配置结构信息
     *
     * @param string $asc 应用配置结构
     * @return array 应用配置结构信息
     */
    protected function _getInfo($asc)
    {
        if (isset($this->_cache[$asc])) {
            return $this->_cache[$asc];
        }

        // 提取附加部分
        /*if (false !== ($pos = strrpos($asc, '#'))) {
            $more = substr($asc, $pos + 1);
            $asc = substr($asc, 0, $pos);
            $more = $more . '%s';
        } elseif (false !== ($pos = strrpos($asc, '@'))) {
            $more = substr($asc, $pos + 1);
            $asc = substr($asc, 0, $pos);
            $more = '%s_' . $more;
        } else {
            $more = '%s';
        }
        $class = strtr(dirname($asc), '/', '_') . '_' . $more;
        echo $class;exit;*/

        // 补全根模块和操作名称
        if ('/' == substr($asc, 0, 1)) {
            $asc = $this->_option['rootModule'] . $asc;
        }
        if ('/' == substr($asc, -1)) {
            $asc .= $this->_option['action'];
        }
        $parts = explode('/', $asc);
        if (3 > count($parts)) {
            throw new Exception('Asc(Application structure configuration) should contain at lease 3 parts: root module, module and action.');
        }

        // 构建信息
        $action = array_pop($parts);
        $this->_cache[$asc] = array(
            'modules' => $parts,
            'module' => end($parts),
            'action' => $action,
            'path' => dirname($asc),
        );
        return $this->_cache[$asc];
    }

    /**
     * 获取根模块的名称
     *
     * @param string $asc 应用结构配置
     * @return string 模块名称
     */
    public function getRootModule($asc = null)
    {
        $info = isset($asc) ? $this->_getInfo($asc) : $this->_info;
        return $info['modules'][0];
    }

    /**
     * 获取模块列表
     *
     * @param string $asc 应用结构配置
     * @return array 模块列表
     */
    public function getModules($asc = null)
    {
        $info = isset($asc) ? $this->_getInfo($asc) : $this->_info;
        return $info['modules'];
    }

    /**
     * 获取操作名称
     *
     * @param string $asc 应用结构配置
     * @return string 操作名称
     */
    public function getAction($asc = null)
    {
        $info = isset($asc) ? $this->_getInfo($asc) : $this->_info;
        return $info['action'];
    }

    /**
     * 获取当前模块的路径
     *
     * @param string $asc 应用结构配置
     * @return string 路径
     */
    public function getPath($asc = null)
    {
        $info = isset($asc) ? $this->_getInfo($asc) : $this->_info;
        return $info['path'];
    }

    public function getClassPart($asc = null)
    {
        $info = isset($asc) ? $this->_getInfo($asc) : $this->_info;
        return strtr($info['path'], '/', '_');
    }

    public function getClass($asc, $type)
    {
        if (is_string($asc)) {
            if (false !== ($pos = strrpos($asc, '#'))) {
                $more = substr($asc, $pos + 1);
                $asc = substr($asc, 0, $pos);
                $more = $more . $type;
            } elseif (false !== ($pos = strrpos($asc, '@'))) {
                $more = substr($asc, $pos + 1);
                $asc = substr($asc, 0, $pos);
                $more = $type . '_' . $more;
            } else {
                $more = $type;
            }
            $class = strtr(dirname($asc), '/', '_') . '_' . $more;
        }
        echo $class;
    }

    static function getClassPart2($asc, $type)
    {
        if (is_string($asc)) {
            if (false !== ($pos = strrpos($asc, '#'))) {
                $more = substr($asc, $pos + 1);
                $asc = substr($asc, 0, $pos);
                $more = $more . $type;
            } elseif (false !== ($pos = strrpos($asc, '@'))) {
                $more = substr($asc, $pos + 1);
                $asc = substr($asc, 0, $pos);
                $more = $type . '_' . $more;
            } else {
                $more = $type;
            }
            $class = strtr(dirname($asc), '/', '_') . '_' . $more;
            /*if (isset(self::$_cache[$asc])) {
                return self::$_cache[$asc];
            }*/
        } elseif (is_array($asc)) {
            if (isset($asc['#'])) {
                $more = $asc['#'] . $type;
                unset($asc['#']);
            } elseif (isset($asc['@'])) {
                $more = $type . '_' . $asc['@'];
                unset($asc['@']);
            } else {
                $more = $type;
            }
            $class = implode('_', $asc) . '_' . $more;
        } else {
            throw new Exception('Asc(Application structure configuration) should be string or array.');
        }
        return $class;
    }
}
