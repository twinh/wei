<?php
/**
 * Article
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
 * @package     Trex
 * @subpackage  Article
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @since       2010-04-17 14:02:35
 */

class Trex_Article_Controller_Article extends Trex_ActionController
{

    /**
     * 生成静态页面
     * @todo 如何使用视图元素
     */
    /*public function actionHtml()
    {
        $ini = Qwin::run('-ini');
        $gpc = Qwin::run('-gpc');
        $meta = &$this->__meta;

        // 加载关联模型,元数据
        $this->meta->loadRelatedData($meta['model']);
        // 获取模型类名称
        $modelName = $ini->getClassName('Model', $this->_set);
        $query = $this->meta->connectModel($modelName, $meta['model']);
        $meta = $this->meta->connetMetadata($meta);

        // 转换成List是为了使用List的转换函数
        $this->setAction('List');

        // 根据url参数中的值,获取对应的数据库资料
        $id = $gpc->g($meta['db']['primaryKey']);
        $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
        if(false == $query)
        {
            $this->Qwin_Helper_Js->show($this->t('MSG_NO_RECORD'));
        }
        $dbData = $query->toArray();
        // 根据配置和控制器中的对应方法转换数据
        $dbData = $this->meta->convertSingleData($meta['field'], $this->_set['action'], $dbData);

        $this->createHtml($dbData);

        if(isset($_SERVER['HTTP_REFERER']))
        {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = url(array($self->__query['namespace'], $self->__query['module'], $self->__query['controller']));
        }
        Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_OPERATED_SUCCESS'), $url);
        
    }*/

    /*public function actionCreateAllHtml()
    {
        $query = $this->meta->getQuery($this->_set);
        $dbData = $query->execute()->toArray();
        foreach($dbData as $data)
        {
            $this->createHtml($data);
        }
        //if(isset($_SERVER['HTTP_REFERER']))
        //{
        //    $url = $_SERVER['HTTP_REFERER'];
        //} else {
            $url = url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller']));
        //}
        Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_OPERATED_SUCCESS'), $url);
    }*/

    public function createHtml($data)
    {
        // 检查模板文件是否存在
        if(!file_exists($data['template']))
        {
            Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_TEMPLATE_NOT_EXISTS'), url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'], 'Edit'), array('id' => $data['id'])));
        }
        if(!empty($data['page_name']))
        {
            $path = $data['page_name'];
        } else {
            $path = 'article-' . $data['id'] . '.html';
        }


        // 初始化视图变量数组
        $this->__view = array(
            'set' => &$meta,
            'data' => &$data,
            'primaryKey' => $meta['db']['primaryKey'],
        );

        ob_start();
        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
        //$this->loadView($data['template'], false);
        require $data['template'];

        $output = ob_get_contents();
        '' != $output && ob_end_clean();
        file_put_contents($path, $output);
    }

    /*public function convertListId($val)
    {
        if(isset($_GET['searchField']) && 'category_2' == $_GET['searchField'])
        {
            $this->__meta['field']['category_id']['converter']['list'] = array(
                array('Project_Hepler_Category', 'convertTreeResource'),
                array(
                    'namespace' => 'Default',
                    'module' => 'Category',
                    'controller' => 'Category',
                ),
                $_GET['searchValue']
            );
        }
        return $val;
    }*/

    public function convertAddCategoryId($val, $name, $data, $copyData)
    {
        if(isset($_GET['sign']))
        {
            $this->__meta['field']['category_id']['form']['_resourceGetter'] = array(
                array('Project_Hepler_Category', 'getTreeResource'),
                array(
                    'namespace' => 'Default',
                    'module' => 'Category',
                    'controller' => 'Category',
                ),
                $_GET['sign']
            );
        }
        return $val;
    }

    public function convertAddCategory2()
    {
        if(isset($_GET['sign']))
        {
            return $_GET['sign'];
        }
    }

    /*public function convertEditCategoryId($val, $name, $data)
    {
        // 专题
        if(NULL != $data['category_2'])
        {
            $this->__meta['field']['category_id']['form']['_resourceGetter'] = array(
                array('Project_Hepler_Category', 'getTreeResource'),
                array(
                    'namespace' => 'Default',
                    'module' => 'Category',
                    'controller' => 'Category',
                ),
                $data['category_2']
            );
        }
        return $val;
    }*/

    public function convertListTitle($value, $name, $data, $copyData)
    {
        return Qwin_Helper_Html::titleDecorator($value, $copyData['title_style'], $copyData['title_color']);
    }

    public function convertViewTitle($value, $name, $data, $copyData)
    {
        return Qwin_Helper_Html::titleDecorator($value, $copyData['title_style'], $copyData['title_color']);
    }

    public function convertEditTitleStyle($value, $name, $data, $copyData)
    {
        return explode('|', $value);
    }

    public function convertEditTitleColor($value, $name, $data, $copyData)
    {
        null == $value && $value = 'NULL';
        return $value;
    }

    /**
     *
     * @todo 检查
     */
    public function convertDbTitleStyle($value, $name, $data, $copyData)
    {
        return implode('|', (array)$value);
    }

    public function convertDbTitleColor($value, $name, $data, $copyData)
    {
        'NULL' == $value && $value = null;
        return $value;
    }

    public function convertDbDetailMeta($value, $name, $data, $copyData)
    {
        return serialize(array(
            'keywords' => $copyData['detail_meta_keywords'],
            'description' => $copyData['detail_meta_description'],
        ));
    }

    public function convertEditDetailMeta($value, $name, $data, $copyData)
    {
        return unserialize($value);
    }

    public function convertEditDetailMetaKeywords($value, $name, $data, $copyData)
    {
        return $data['detail_meta']['keywords'];
    }

    public function convertEditDetailMetaDescription($value, $name, $data, $copyData)
    {
        return $data['detail_meta']['description'];
    }

    public function convertViewDetailMeta($value, $name, $data, $copyData)
    {
        return unserialize($value);
    }

    public function convertViewDetailMetaKeywords($value, $name, $data, $copyData)
    {
        return $data['detail_meta']['keywords'];
    }

    public function convertViewDetailMetaDescription($value, $name, $data, $copyData)
    {
        return $data['detail_meta']['description'];
    }
}