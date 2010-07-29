<?php
/**
 * Namespace
 *
 * Copyright (c) 2009-2010 Twin Huang. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <Twin Huang>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-4-6 20:47:47
 * @since     2010-4-6 20:47:47 utf-8 中文
 */

class Qwin_Miku_Namespace
{
    public function beforeLoad($set = null, $config = null)
    {
        $ini = Qwin_Class::run('-ini');
        $config = $ini->getConfig();

        $this->_setupSession($config);
        !defined('TIMESTAMP') && define('TIMESTAMP', time());

        // 打开缓冲区
        ob_start();

        // 增加类的对应关系
        Qwin_Class::addMap(array(
            // 权限控制
            '-acl'  => 'Qwin_Acl',

            // 前端数据处理
            '-wfe'  => 'Qwin_Wfe',
            '-js'   => 'Qwin_Wfe_Js',
            '-css'  => 'Qwin_Wfe_Css',
            '-html' => 'Qwin_Wfe_Html',
            '-rsc'  => 'Qwin_Wfe_Resource',

            // 表单生成
            '-form' => 'Qwin_Form',
            '-btn'  => 'Qwin_Form_Button',

            '-str'  => 'Qwin_Converter_String',
        ));
      
        // 数据库连接
        if($config['db']['setup'])
        {
            if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
            {
                $name = 'db';                
            } else {
                $name = 'web_db';
            }
        }
        require_once(QWIN_LIB_PATH . '/Doctrine.php');
        spl_autoload_register(array('Doctrine', 'autoload'));

        $adapter = $config[$name]['type'] . '://'
                 . $config[$name]['username'] . ':'
                 . $config[$name]['password'] . '@'
                 . $config[$name]['server'] . '/'
                 . $config[$name]['database'];
        $conn = Doctrine_Manager::connection($adapter, $config['project']['name']);
        $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
        $conn->setCharset($config['db']['charset']);
        //$this->profiler = new Doctrine_Connection_Profiler();
        //$conn->setListener($this->profiler);
        
        // 检查权限
        /*if(!qw('-acl')->isPermit($set))
        {
            $set['controller'] = 'HttpError';
            $set['action'] = 401;
            return false;
        }*/

        // 加载表单生成类
        // 表单基本类型,富文本编辑器, jQuery 相关的插件
        Qwin_Class::run('-form')
            ->add('Qwin_Form_Element_Base')
            ->addExt('Qwin_Form_ElementExt_Editor')
            ->addExt('Qwin_Form_ElementExt_JQuery');

        // 加载按钮生成类
        // 部分常用按钮
        Qwin_Class::run('-btn')->add('Qwin_Form_Button_JQuery');
    }

    public function onLoad($set = null, $config = null)
    {
        $ini = Qwin_Class::run('-ini');
        $controller = Qwin_Class::run('-c');
        // 加载配置文件
        
        Qwin_Class::load('Qwin_Miku_Metadata');
        $metadataName = $ini->getClassName('Metadata', $set);
        $metadata = Qwin_Class::run($metadataName);
        if(NULL == $metadata)
        {
            $metadataName = 'Qwin_Miku_Metadata';
            $metadata = Qwin_Class::run($metadataName);
        }
        Qwin_Class::addMap('-s', $metadataName);
        // TODO 是否要包含在控制器中?
        $controller->__meta = $metadata->defaultMetadata();

        
        $metadata->setMetadata();
        print_r($metadata);exit;

        $controller->meta = $metadata;
        // 语言转换
        $this->_loadLang($set, $config);
        $controller->__meta = $metadata->converLang($controller->__meta, $controller->lang);

        // 获取模型类名称
        $modelName = $ini->getClassName('Model', $set);
        $model = Qwin_Class::run($modelName);
        if(NULL == $model)
        {
            $modelName = 'Qwin_Miku_Model';
            $model = Qwin_Class::run($modelName);
        }
        Qwin_Class::addMap('-model', $modelName);
        $controller->model = $model;
        if(isset($controller->__meta['db']['table']))
        {
            $controller->meta->metadataToModel($controller->__meta, $model);
        }
    }

    public function afterLoad($set = null, $config = null)
    {
        /*$time = 0;
        foreach ($this->profiler as $event) {
            $time += $event->getElapsedSecs();
            echo $event->getName() . " " . sprintf("%f", $event->getElapsedSecs()) . "\n";
            echo $event->getQuery() . "\n";
            $params = $event->getParams();
            if( ! empty($params)) {
                print_r($params);
            }
        }
        echo "Total time: " . $time  . "\n";*/
        $controller = Qwin_Class::run('-c');

        $js_lang = 'Qwin.Lang = ';
        $js_lang .= Qwin_Class::run('-arr')->toJsObject($controller->lang);
        $js_lang .= ';';
        Qwin_Class::run('-js')->addJs('lang', $js_lang);
        $output = ob_get_contents();
        '' != $output && ob_end_clean();
        $html = Qwin_Class::run('-html')->packAll();
        $js =  Qwin_Class::run('-js')->packAll();
        $css = Qwin_Class::run('-css')->packAll();
        $output = str_replace(
            array('<!--{CSS}-->', '<!--{JS}-->'),
            array($css, $html . $js),
            $output
        );
        echo $output;
        unset($output);
    }

    /**
     * 加载语言
     * @param array $set
     * @param array $cofig
     */
    private function _loadLang($set = null, $config = null)
    {
        // TODO default language
        // 获取语言名称
        $ini = Qwin_Class::run('-ini');
        $url = Qwin_Class::run('-url');
        $controller = Qwin_Class::run('-c');

        $lang = '';
        // 按优先级排列语言的数组
        $lang_arr = array(
            $url->g('lang'),
            Qwin_Class::run('-ses')->get('lang'),
            $config['i18n']['language'],
        );
        foreach($lang_arr as $val)
        {
            if(null != $val)
            {
                $lang = $val;
                break;
            }
        }
        Qwin_Class::run('-ses')->set('lang', $lang);
        //$controller->lang_name = $lang;
        $controller->meta->lang = $lang;

        // 加载项目语言
        $lang_file = ROOT_PATH . '/Common/Lang/' . $lang . '.php';
        if(file_exists($lang_file))
        {
            $controller->lang = require_once $lang_file;
        } else {
            $lang_file = ROOT_PATH . '/Common/Lang/' . $config['i18n']['language'] . '.php';
            $controller->lang = require_once $lang_file;
        }

        // 加载当前模块语言
        $module_lang_file = ROOT_PATH . '/App/' . $set['namespace'] . '/' . $set['module'] . '/Lang/' . $lang . '.php';
        if(file_exists($module_lang_file))
        {
            $controller->lang += require_once $module_lang_file;
        } else {
            $module_lang_file = ROOT_PATH . '/App/' . $set['namespace'] . '/' . $set['module'] . '/Lang/' . $config['i18n']['language'] . '.php';
            if(file_exists($module_lang_file))
            {
                $controller->lang += require_once $module_lang_file;
            }
        }
    }

    /**
     * 启动会话
     * @param array $config 配置数组
     */
    private function _setupSession($config)
    {
        Qwin_Class::addMap('-ses', 'Qwin_Session');
        $namespace = md5($_SERVER['SERVER_NAME'] . $config['project']['name']);
        Qwin_Class::run('-ses', $namespace);
    }
}
