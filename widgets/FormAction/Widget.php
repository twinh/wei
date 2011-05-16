<?php
/**
 * Widget
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
 * @since       2011-05-11 01:09:44
 */

class FormAction_Widget extends Qwin_Widget_Abstract
{
    /**
     * @var array           默认选项
     * 
     *      -- form         表单元数据对象
     * 
     *      -- id           主键的值
     * 
     *      -- data         初始值
     * 
     *      -- sanitise     转换配置
     * 
     *      -- display      是否显示视图
     */
    protected $_defaults = array(
        'form'      => null,
        'id'        => null,
        'data'      => array(),
        'asAction'  => 'view',
        'isView'    => true,
        'sanitise'  => array(
            
        ),
        'display'   => true,
    );
    
    public function render($options = null)
    {
        // 初始配置
        $options    = (array)$options + $this->_options;
        
        /* @var $listMeta Qwin_Meta_Form */
        $form = $options['form'];
        
        // 检查表单元数据是否合法
        if (!is_object($form) || !$form instanceof Qwin_Meta_Form) {
            $this->e('ERR_META_ILLEGAL');
        }
        $meta = $form->getParent();
        $id = $meta['db']['id'];
        
        // 从模型获取数据
        $query = $meta->getQuery(null, array('type' => array('db', 'view')));
        $dbData = $query
            ->where($id . ' = ?', $options['id'])
            ->fetchOne();
        
        // 记录不存在,加载错误视图
        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang['MSG_NO_RECORD'],
            );
            if ($options['display']) {
                return Qwin::call('-view')->alert($result['message']);
            } else {
                return $result;
            }
        }
        $data = $dbData->toArray();
        
        // 转换数据
        if ($options['sanitise']) {
            $data = $meta->sanitise($data, $options['sanitise']);
        }
        
        // 返回结果
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }

        // 展示视图
        if ($options['display']) {
            $view = Qwin::call('-view')->assign(get_defined_vars());
            if ('view' == $options['asAction']) {
                $this->_processView();
            } elseif ('edit' == $options['asAction']) {
                $this->_processEdit();
            } else {
                // todo error or other
            }
        }

        return $result;
    }
    
    /**
     * 处理视图
     * @todo 作为新的微件
     */
    protected function _processView()
    {
        $view = Qwin::call('-view');
        $view->setElement('content', '<root>com/basic/view<suffix>');
        $view['module'] = $view['options']['module'];
        $view['action'] = 'view';

        // 初始化变量,方便调用
        $primaryKey     = $view->primaryKey;
        $meta           = $view->meta;
        $data           = $view->data;
        $request        = Qwin::call('-request');
        $config         = Qwin::config();
        $url            = Qwin::call('-url');
        $lang           = Qwin::call('-lang');
        $widget         = Qwin::call('-widget');

        /* @var $formWidget Form_Widget */
        $formWidget = $widget->get('Form');
        $formOptions = array(
            'meta'      => $meta,
            'action'    => 'view',
            'data'      => $view->data,
            'view'      => 'view.php',
        );

        /* @var $jqGridWidget JqGrid_Widget */
        $jqGridWidget   = $widget->get('JqGrid');
        $jqGridOptions  = array();

        // 关联列表的数据配置
        //$relatedListConfig = $metaHelper->getRelatedListConfig($meta);
        /* @var $meta Qwin_Application_Meta */
        $relatedListMetaList = $meta->getModelMetaByType('relatedList');

        // 构建每一个的jqgrid数据
        $jqGridList = $tabTitle = $moduleLang = array();
        foreach ($relatedListMetaList as $alias => $relatedMeta) {
            $jqGrid = array();
            $model = $meta['model'][$alias];

            $lang->appendByModule($model['module']);

            $tabTitle[$alias] = $lang[$relatedMeta['page']['title']];


            // 获取并合并布局
            $listLayout = $jqGridWidget->getLayout($relatedMeta);
            if (null != $model['list']) {
                $listLayout = array_intersect($listLayout, (array)$model['list']);
            }
            // 删除外键域,外键域显示的内容即为当前视图的内容
            $key = array_search($model['foreign'], $listLayout);
            if (false !== $key) {
                unset($listLayout[$key]);
            }
            $col = $jqGridWidget->getColByListLayout($listLayout, $relatedMeta, $lang);
            $options['colNames'] = $col['colNames'];
            $options['colModel'] = $col['colModel'];

            // 获取json数据的地址
            $options['url'] = $url->url($model['module'], 'index', array(
                'json'      => '1',
                'search'    => $model['foreign'] . ':' . $data[$model['local']],
                'list'      => implode(',', $listLayout),
            ));

            $jqGrid = array(
                'module'    => new Qwin_Module($model['module']),
                'meta'      => $relatedMeta,
                'layout'    => (array)$model['list'],
                'options'    => $options,
            );

            $jqGridList[$alias] = $jqGrid;
        }
        $group = $meta['group'];

        $operLinks = $widget->get('OperLinks')->render($view);

        $view->assign(get_defined_vars());
    }
}
