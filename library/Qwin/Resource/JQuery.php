<?php
/**
 * JQuery
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
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-19 14:55:28
 * @todo        合法版本对齐,参阅谷歌API
 * @todo        允许自定义ui,plugin,effect路径,命名规则
 */

class Qwin_Resource_JQuery extends Qwin_Resource
{
    /**
     * 是否加载压缩过的脚本文件
     * @var boolen
     * @todo available
     */
    protected $_isLoadMiniFile = true;
    
    /**
     * 主题名称
     * @var string
     */
    protected $_theme = 'base';

    protected $_isLoad = array();

    public function __construct()
    {
        parent::__construct();
        $this->_typePath = $this->_rootPath . '/js/jquery';

        $this->_isLoad = array(
            'core', 'theme', 'ui', 'effect', 'plugin',
        );
    }

    public function loadCore($isWrap = true)
    {
        if(isset($this->_isLoad['core']['core']))
        {
            return null;
        }
        $this->_isLoad['core']['core'] = true;
        $file = $this->_typePath . '/jquery.js';
        if(true == $isWrap)
        {
            return $this->wrapAsFile($file);
        }
        return $file;
    }

    public function loadUi($name, $isWrap = true)
    {
        if(isset($this->_isLoad['ui'][$name]))
        {
            return null;
        }
        $this->_isLoad['ui'][$name] = true;
        $file = $this->_typePath . '/ui/jquery.ui.' . $name . '.min.js';
        $cssFile = $this->_typePath . '/ui/jquery.ui.' . $name . '.css';
        if(true == $isWrap)
        {
            if(file_exists($cssFile))
            {
                return $this->wrapAsCssFile($cssFile) . $this->wrapAsFile($file);
            }
            return $this->wrapAsFile($file);
        }
        return array(
            'js' => $file,
            'css' => $cssFile
        );
    }

    public function loadEffect($name, $isWrap = true)
    {
        if(isset($this->_isLoad['effect'][$name]))
        {
            return null;
        }
        $this->_isLoad['effect'][$name] = true;
        $file = $this->_typePath . '/effects/jquery.effects.' . $name . '.min.js';
        if(true == $isWrap)
        {
            return $this->wrapAsFile($file);
        }
        return $file;
    }

    public function loadPlugin($name, $type = null, $isWrap = true)
    {
        if(isset($this->_isLoad['plugin'][$name]))
        {
            return null;
        }
        $this->_isLoad['plugin'][$name] = true;
        $file = $this->_typePath . '/plugins/' . $name . '/jquery.' . $name;

        if(null == $type)
        {
            $jsFile = $file . '.js';
        } else {
            $jsFile = $file . '.' . $type . '.js';
        }

        $cssFile = $file . '.css';
        if(true == $isWrap)
        {
            if(file_exists($cssFile))
            {
                return $this->wrapAsCssFile($cssFile) . $this->wrapAsFile($jsFile);
            }
            return $this->wrapAsFile($jsFile);
        }
        return array(
            'js' => $jsFile,
            'css' => $cssFile,
        );
    }

    public function wrapCode($code)
    {
        return '<script type="text/javascript">jQuery(function($){' . $code .  '});</script>' . "\r\n";
    }

    public function wrapAsFile($file)
    {
        return '<script type="text/javascript" src="' . $file . '"></script>' . "\r\n";
    }

    public function wrapAsCssFile($file, $type = null)
    {
        return '<link rel="stylesheet" type="text/css" media="all" href="' . $file . '" />' . "\r\n";
    }
}
