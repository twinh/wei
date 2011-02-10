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
 * @package     Common
 * @subpackage  Article
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @since       2010-04-17 14:02:35
 */

class Common_Article_Controller_Article extends Common_ActionController
{

    /**
     * 生成静态页面
     * @todo 如何使用视图元素
     */
    /*public function actionHtml()
    {
        $ini = Qwin::call('-ini');
        $gpc = Qwin::call('-gpc');
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
        $id = $gpc->get($meta['db']['primaryKey']);
        $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
        if(false == $query)
        {
            $this->Qwin_Helper_Js->show($this->t('MSG_NO_RECORD'));
        }
        $dbData = $query->toArray();
        // 根据配置和控制器中的对应方法转换数据
        $dbData = $this->meta->filterOne($meta['field'], $this->_set['action'], $dbData);

        $this->createHtml($dbData);
        
    }

    public function actionCreateAllHtml()
    {
    }

    public function createHtml($data)
    {
        // 检查模板文件是否存在
        if(!file_exists($data['template']))
        {
            Qwin::call('Qwin_Helper_Js')->show($this->t('MSG_TEMPLATE_NOT_EXISTS'), url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'], 'Edit'), array('id' => $data['id'])));
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
    }*/
}
