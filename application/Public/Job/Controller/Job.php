<?php
/**
 * Job
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
 * @package     Public
 * @subpackage  Job
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 0:39:08
 */

class Public_Job_Controller_Job extends Public_Controller
{
    public function actionList()
    {
        $request = $this->request;
        $commonClass = Qwin::run('Project_Helper_CommonClass');
        $metaHelper = $this->metaHelper;
        $where = null;
        
        /**
         * 工作类型 兼职1 招聘2
         */
        $type = $request->g('type');

        // 岗位类型
        $urlWorkType = $request->g('work-type');
        $workType = $commonClass->get('work-type');
        if(0 != $urlWorkType && isset($workType[$urlWorkType]))
        {
            $where[] = array('work_type', $urlWorkType);
        } else {
            $urlWorkType = 0;
        }
        $workType[0] = '全部';

        // 学历
        $urlEducation = $request->g('education');
        $education = $commonClass->get('job-education');
        if(isset($education[$urlEducation]))
        {
            $where[] = array('education', $urlEducation);
        }

        // 地区
        $urlJobArea = $request->g('jobArea');
        $jobArea = $commonClass->get('job-area');
        if(isset($jobArea[$urlJobArea]))
        {
            $where[] = array('area', $urlJobArea);
        }

        // 发布日期
        $date = $request->g('date');
        switch($date)
        {
            case '1':
                $realDate = '+0 day';
                break;
            case '2':
                $realDate = '-2 day';
                break;
            case '3':
                $realDate = '-4 day';
                break;
            case '4':
                $realDate = '-6 day';
                break;
            default:
                break;
        }
        if(isset($realDate))
        {
            $where[] = array('date_modified', date('Y-m-d',strtotime($realDate)) . ' 00:00:00', 'gt');
        }

        // 页面
        $page = intval($this->request->g('page'));
        $page <= 0 && $page = 1;

        // 从模型获取数据
        $set = array(
            'namespace' => 'Trex',
            'module' => 'Job',
            'controller' => 'Job',
        );
        $query = $metaHelper->getDoctrineQuery($set);
        $meta = $metaHelper->getMetadataBySet($set);
        $metaHelper
            ->addSelectToQuery($meta, $query)
            ->addOrderToQuery($meta, $query)
            ->addWhereToQuery($meta, $query, $where);
        $query
            ->offset(($page - 1) * $meta['db']['limit'])
            ->limit($meta['db']['limit']);
        $data = $query->execute()->toArray();
        $count = count($data);

        // 分页
        $page = array(
            'url' => '?',
            'nowPage' => $page,
            'count' => $query->count(),
            'row' => $meta['db']['limit'],
        );
        $pageCode = Project_Hepler_Page::create($page);

        foreach($data as $key => &$row)
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

            // 推荐
            if(1 == $row['is_recommend'])
            {
                $row['is_recommend_text'] = '<span style="color:red">[荐]</span>';
            } else {
                $row['is_recommend_text'] = '';
            }

            // 学历
            $row['education'] = $education[$row['education']];
            // 地区
            $row['area'] = $jobArea[$row['area']];
            $row['date_modified'] = substr($row['date_modified'], 0, 10);
        }

        // 设置视图
        $this->_view = array(
            'class' => 'Public_View',
            'element' => array(
                array('content', './view/job/list.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    public function actionView()
    {
        $request = $this->request;
        $commonClass = Qwin::run('Project_Helper_CommonClass');
        $id = $request->g('id');

        $metaHelper = $this->metaHelper;
        // 从模型获取数据
        $set = array(
            'namespace' => 'Trex',
            'module' => 'Job',
            'controller' => 'Job',
        );
        $query = $metaHelper->getDoctrineQuery($set);
        
        $result = $query->where('id = ?', $id)->fetchOne();
        if(false == $result)
        {
            $this->setRedirectView('记录不存在或已经删除.');
            return false;
        }
        $data = $result->toArray();

        // 数目
        if(0 == $data['number'])
        {
            $data['number'] = '若干';
        }
        // 薪资
        if(0 == $data['salary_from'] && 0 == $data['salary_to'])
        {
            $data['salary'] = '面议';
        } else {
            $data['salary'] = $data['salary_from'] . '-' . $data['salary_to'] . '元/月';
        }
        // 推荐
        if(1 == $data['is_recommend'])
        {
            $data['is_recommend_text'] = '<span style="color:red">[荐]</span>';
        } else {
            $data['is_recommend_text'] = '';
        }
        // 更新日期
        $data['date_modified'] = substr($data['date_modified'], 0, 10);

        // 资源
        $companySize = $commonClass->get('company-size');
        $education = $commonClass->get('job-education');
        $workSeniority = $commonClass->get('work-seniority');
        $companyNature = $commonClass->get('company-nature');
        $companyIndustry = $commonClass->get('company-industry');
        
        // 找出该公司的所有职位
        $relatedData = $query->where('related_id = ?', $data['related_id'])->execute()->toArray();
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
            // 推荐
            if(1 == $row['is_recommend'])
            {
                $row['is_recommend_text'] = '<span style="color:red">[荐]</span>';
            } else {
                $row['is_recommend_text'] = '';
            }
            // 学历
            $row['education'] = $education[$row['education']];
            $row['date_modified'] = substr($row['date_modified'], 0, 10);
        }

        // 设置视图
        $this->_view = array(
            'class' => 'Public_View',
            'element' => array(
                array('content', './view/job/view.php'),
            ),
            'data' => get_defined_vars(),
        );
    }
}