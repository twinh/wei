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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-27 20:35:11
 */

class Qwin_Helper_JQuery
{
    protected $_isLoad = array('core', 'ui', 'effect', 'plugin');

    public function __construct($rootPath = null)
    {
        $this->_rootPath = $rootPath;
    }

    public function loadCore($isWrap = true)
    {
        if(isset($this->_isLoad['core']['core']))
        {
            return null;
        }
        $this->_isLoad['core']['core'] = true;
        $file = $this->_rootPath . '/jquery.js';
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
        $file = $this->_rootPath . '/ui/jquery.ui.' . $name . '.min.js';
        $cssFile = $this->_rootPath . '/ui/jquery.ui.' . $name . '.css';
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
        $file = $this->_rootPath . '/effects/jquery.effects.' . $name . '.min.js';
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
        $file = $this->_rootPath . '/plugins/' . $name . '/jquery.' . $name;

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