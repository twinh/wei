<?php
/**
 * View
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
 * @package     Com
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id: View.php 556 2011-04-17 13:32:39Z itwinh@gmail.com $
 * @since       2010-10-11 10:35:49
 */

class View_Widget extends Qwin_Widget_Abstract
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'meta'      => null,
        'id'        => null,
        'asAction'  => 'view',
        'isView'    => true,
        'sanitise'  => true,
        'display'   => true,
    );

    public function execute(array $options = null)
    {
        // 初始配置
        $options    = array_merge($this->_options, $options);

        /* @var $meta Com_Metadata */
        $meta = null == $options['meta'] ? Com_Metadata::getByModule($options['module']) : $options['meta'];
        $primaryKey = $meta['db']['primaryKey'];

        // 从模型获取数据
        $query = Com_Metadata::getQueryByModule($options['module'], array('type' => array('db', 'view')));
        $dbData = $query
            ->where($primaryKey . ' = ?', $options['id'])
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

        // 设置钩子:查看数据
        Qwin::hook('viewRecord', array(
            'record' => $dbData,
            'meta' => $meta,
        ));

        // 转换数据
        if ($options['sanitise']) {
            $data = $meta->sanitise($data, $options['asAction'], array('view' => $options['isView']));
        }

        // 设置返回结果
        $result = array(
            'result' => true,
            'data' => get_defined_vars(),
        );

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
        /* @var $meta Qwin_Application_Metadata */
        $relatedListMetaList = $meta->getModelMetadataByType('relatedList');

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

    /**
     * 处理编辑操作
     *
     * @todo 作为新的微件
     */
    protected function _processEdit()
    {
        $view = Qwin::call('-view');
        $view->setElement('content', '<root>com/basic/form<suffix>');
        $view['module'] = $view['options']['module'];
        $view['action'] = 'edit';

        // 初始化变量,方便调用
        $primaryKey = $view->primaryKey;

        $meta = $view->meta;
        $data = $view->data;

        /* @var $formWidget Form_Widget */
        $formWidget = Qwin::call('-widget')->get('Form');
        $formOptions = array(
            'meta'  => $meta,
            'action' => 'edit',
            'data'  => $view->data,
        );

        $operLinks = Qwin::call('-widget')->get('OperLinks')->render($view);

        $view->assign(get_defined_vars());
    }
}
