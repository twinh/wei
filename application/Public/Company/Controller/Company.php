<?php
/**
 * Company
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
 * @since       2010-10-18 16:06:43
 */

class Public_Company_Controller_Company extends Public_Controller
{
    public function actionView()
    {
        $request = $this->request;
        $commonClass = Qwin::run('Project_Helper_CommonClass');
        $id = $request->g('id');
        $metaHelper = $this->metaHelper;

        /**
         * @see Trex_Service_View $_config
         */
        $config = array(
            'set' => array(
                'namespace' => 'Trex',
                'module' => 'Company',
                'controller' => 'Company',
            ),
            'data' => array(
                'primaryKeyValue' => $id,
            ),
            'view' => array(
                'display' => false,
            )
        );
        $result = Qwin::run('Trex_Service_View')->process($config);
        if(false == $result['result'])
        {
            return $this->setRedirectView($result['message']);
        }
        $data = $result['data'];

        // 从模型获取数据
        $set = array(
            'namespace' => 'Trex',
            'module' => 'Job',
            'controller' => 'Job',
        );
        $query = $metaHelper->getDoctrineQuery($set);
        // 找出该公司的所有职位
        $relatedData = $query->where('related_id = ?', $data['id'])->execute()->toArray();
        $education = $commonClass->get('job-education');
        foreach($relatedData as &$row)
        {
            // 薪资
            if(0 == $row['salary_from'] && 0 == $row['salary_to'])
            {
                $row['salary'] = '面议';
            } else {
                $row['salary'] = $row['salary_from'] . '-' . $row['salary_to'];
            }
            // 数目
            if(0 == $row['number'])
            {
                $row['number'] = '若干';
            }
            // 学历
            $row['education'] = $education[$row['education']];
            $row['date_modified'] = substr($row['date_modified'], 0, 10);
        }

        // 设置视图
        $this->_view = array(
            'class' => 'Public_View',
            'element' => array(
                array('content', './view/company/view.php'),
            ),
            'data' => get_defined_vars(),
        );
    }
}
