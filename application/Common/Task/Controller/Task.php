<?php
/**
 * Task
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
 * @subpackage  Task
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-19 09:34:20
 */

class Common_Task_Controller_Task extends Common_ActionController
{
    protected $_status;

    /**
     * 查看分配给我的任务
     */
    public function actionListAssignToMe() 
    {
        $_GET['searchField'] = 'assign_to';
        $_GET['searchValue'] = $this->member['id'];
        $this->_meta['page']['title'] = 'LBL_MODULE_TASK_ASSIGN_TO_ME';
        parent::actionIndex();
    }

    /**
     * 查看我分配的任务
     */
    public function actionListAssignByMe()
    {
        $_GET['searchField'] = 'assign_by';
        $_GET['searchValue'] = $this->member['id'];
        $this->_meta['page']['title'] = 'LBL_MODULE_TASK_ASSIGN_BY_ME';
        parent::actionIndex();
    }

    public function actionListMyNewTask()
    {
        // TODO
    }

    /**
     * 添加任务,属于任务的第一个流程
     */
    public function actionAdd()
    {
        $this->_status = 'new';
        $this->_meta['field']->unlink(array('is_post_email', 'assign_to', 'assign_by'));
        parent::actionAdd();
    }

    /**
     * 分配任务,属于任务的第二个流程
     */
    public function actionAssignTo()
    {
        $this->_status = 'assignTo';
        $this->_meta['field']->setReadonly(array(
            'name', 'description',
        ));
        parent::actionEdit();
    }

    /**
     * 解决任务,只有被分配到的人才能解决任务,属于任务的第三个流程
     */
    public function actionResolve()
    {
        $this->_status = 'resolve';
        $primaryKey = $this->_meta['db']['primaryKey'];

        // TODO
        $_POST[$primaryKey] = $this->request->g($primaryKey);
        $_POST['modified_by'] = $this->member['id'];

        // 删除无关键名
        $exceptKey = array($primaryKey, 'status', 'assign_to', 'date_modified', 'modified_by');
        $this->_meta['field']->unlinkExcept($exceptKey);
        $this->_meta['model']->unlinkExcept($exceptKey);
        
        parent::actionEdit();
    }

    /**
     * 检查任务,只有被分配者才能检查任务,属于任务的第四个流程
     */
    public function actionCheck()
    {
        $this->_status = 'check';
        $primaryKey = $this->_meta['db']['primaryKey'];

        // TODO
        $_POST[$primaryKey] = $this->request->g($primaryKey);
        $_POST['modified_by'] = $this->member['id'];

        // 删除无关键名
        $exceptKey = array($primaryKey, 'status', 'assign_by', 'date_modified', 'modified_by');
        $this->_meta['field']->unlinkExcept($exceptKey);
        $this->_meta['model']->unlinkExcept($exceptKey);

        parent::actionEdit();
    }

    /**
     * 关闭任务,只有创建任务者才能关闭任务,属于任务的第五个流程
     */
    public function actionClose()
    {
        $this->_status = 'close';
        $primaryKey = $this->_meta['db']['primaryKey'];

        // TODO
        $_POST[$primaryKey] = $this->request->g($primaryKey);
        $_POST['modified_by'] = $this->member['id'];

        // 删除无关键名
        $exceptKey = array($primaryKey, 'status', 'date_modified', 'modified_by', 'created_by');
        $this->_meta['field']->unlinkExcept($exceptKey);
        $this->_meta['model']->unlinkExcept($exceptKey);

        parent::actionEdit();
    }

    /**
     * 编辑任务
     */
    public function actionEdit()
    {
        $this->_meta['field']->unlink(array(
            'assign_to', 'assign_by', 'is_post_email',
        ));
        $this->_meta['model']->unlink(array(
            'assign_to', 'assign_by',
        ));
        parent::actionEdit();
    }

    public function convertAssignToStatus($value, $name, $data, $copyData)
    {
        // 该任务已经分配
        if(3 <= $copyData['status'])
        {
            $this
                ->setRedirectView($this->_lang->t('MSG_TASK_HAS_ASSIGNED'))
                ->loadView()
                ->display();
            exit;
        }
        return $value;
    }

    /**
     * 在入库操作下,获取分配者的编号,属于第二阶段
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return int 当前域的新值
     */
    public function convertDbAssignBy($value, $name, $data, $copyData)
    {
        return $this->member['id'];
    }

    public function convertDbStatus($value, $name, $data, $copyData)
    {
        switch($this->_status)
        {
            // 操作为新建任务时,设置状态为新建
            case 'new':
                $value = 1;
                break;

            // 操作为分配时,设置状态为分配
            case 'assignTo':
                if(1 != $value)
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_FOLLOW_FLOW'))
                        ->loadView()
                        ->display();
                    exit;
                }
                $value = 3;
                break;

            // 操作为解决任务,设置状态为已解决
            case 'resolve':
                if(3 != $this->_result['status'])
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_FOLLOW_FLOW'))
                        ->loadView()
                        ->display();
                    exit;
                }
                // 不属于当前用户的任务,无法解决
                if($this->_result['assign_to'] != $this->member['id'])
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_ASSIGN_TO_YOU'))
                        ->loadView()
                        ->display();
                    exit;
                }
                $value = 5;
                break;

            // 操作为检查任务,设置状态为已检查
            case 'check':
                if(5 != $this->_result['status'])
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_FOLLOW_FLOW'))
                        ->loadView()
                        ->display();
                    exit;
                }
                if($this->_result['assign_by'] != $this->member['id'])
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_ASSIGN_BY_YOU'))
                        ->loadView()
                        ->display();
                    exit;
                }
                $value = 7;
                break;
            
            // 操作为关闭任务,设置状态为关闭
            case 'close':
                if(7 != $this->_result['status'])
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_FOLLOW_FLOW'))
                        ->loadView()
                        ->display();
                    exit;
                }
                if($this->_result['created_by'] != $this->member['id'])
                {
                    $this
                        ->setRedirectView($this->_lang->t('MSG_NOT_CREATED_BY_YOU'))
                        ->loadView()
                        ->display();
                    exit;
                }
                $value = 9;
                break;

            default:
                break;
        }
        return $value;
    }

    /**
     * 在查看记录的操作下,显示解决问题的按钮,只有被分配者才能看到该按钮
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return int 当前域的新值
     */
    public function convertViewStatus($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        switch($copyData['status'])
        {
            case 3:
                // 你是被分配的人
                if($copyData['assign_to'] == $this->member['id'])
                {
                    $value = Qwin::run('Project_Helper_CommonClass')->convert($value, 'task-status')
                           . ' '
                           . Qwin_Helper_Html::jQueryLink($this->url->createUrl($this->_set, array('action' => 'Resolve', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_RESOLVED'), 'ui-icon-check', 'ui-view-button');
                }
                break;

            case 5:
                // 你是分配该任务的人
                if($copyData['assign_by'] == $this->member['id'])
                {
                    $value = Qwin::run('Project_Helper_CommonClass')->convert($value, 'task-status')
                           . ' '
                           . Qwin_Helper_Html::jQueryLink($this->url->createUrl($this->_set, array('action' => 'Check', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_CHECKED'), 'ui-icon-check', 'ui-view-button');
                }
                break;

            case 7:
                if($copyData['created_by'] == $this->member['id'])
                {
                    $value = Qwin::run('Project_Helper_CommonClass')->convert($value, 'task-status')
                           . ' '
                           . Qwin_Helper_Html::jQueryLink($this->url->createUrl($this->_set, array('action' => 'Close', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_CLOSED'), 'ui-icon-check', 'ui-view-button');
                }
                break;
        }
        return $value;
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $html = '';
        if(1 == $copyData['status'])
        {
            $html  = Qwin_Helper_Html::jQueryButton($this->url->createUrl($this->_set, array('action' => 'AssignTo', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_ASSIGN_TO'), 'ui-icon-person');
        }
        $html .= parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function onAfterDb($data)
    {
        
        /*if('AssignTo' == $this->getLastAction() && null != $post && 1 == $post[0])
        {
            $memberResult = $this
                ->_meta
                ->getDoctrineQuery(array(
                    'namespace' => 'Common',
                    'module' => 'Member',
                    'controller' => 'Member',
                ))
                ->where('id = ?', $data['assign_to'])
                ->fetchOne();

            $emailSet = array(
                    'namespace' => 'Common',
                    'module' => 'Email',
                    'controller' => 'Email',
                );
            $emailQuery = $this
                ->_meta
                ->getDoctrineQuery($emailSet);
            $ini = Qwin::run('-ini');
            $emailModel = $ini->getClassName('Model', $emailSet);
            $emailQuery = new $emailModel;

            //$emailQuery['id'] = Qwin
            $emailQuery['from'] = 'atwin@qq.com';
            $emailQuery['from_name'] = 'Twin Huang';
            $emailQuery['to'] = $memberResult['email'];
            $emailQuery['to_name'] = $memberResult['username'];
            $emailQuery['subject'] = '您有一份新的任务需要完成: ' . $this->_result['name'];
            $emailQuery['content'] = '您有一份新的任务需要完成: ' . $this->_result['name'] . '<br />' . $this->_result['description'];
            $emailQuery->save();
        }*/
    }

    public function convertDbStatusId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }

    public function convertDbEmailId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }

    public function convertDbEmailTo($value, $name, $data, $copyData)
    {
        $set = array(
            'namespace' => 'Common',
            'module' => 'Member',
            'controller' => 'Member',
        );
        $this->_emailMember = $this
            ->metaHelper
            ->getQueryBySet($set)
            ->where('id = ?', $_POST['assign_to'])
            ->fetchOne();
        return $this->_emailMember['email'];
    }

    public function convertDbEmailToName($value, $name, $data, $copyData)
    {
        return $this->_emailMember['username'];
    }

    public function convertDbEmailFrom($value, $name, $data, $copyData)
    {
        return 'atwin@qq.com';
    }

    public function convertDbEmailFromName($value, $name, $data, $copyData)
    {
        return 'Twin Huang';
    }

    public function convertDbEmailSubject($value, $name, $data, $copyData)
    {
        return '您有一份新的任务需要完成:' . $this->_result['name'];
    }

    public function convertDbEmailContent($value, $name, $data, $copyData)
    {
        $content = file_get_contents(QWIN_RESOURCE_PATH . '/template/email/task.tpl');
        $search = array(
            '{subject}',
            '{content}',
            '{post_time}',
            '{from}',
            '{to}',
            '{url}',
        );
        $replace = array(
            $this->_result['name'],
            $this->_result['description'],
            date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            $data['from_name'] . '&lt;' . $data['from'] . '&gt;',
            $data['to_name'] . '&lt;' . $data['to'] . '&gt;',
            'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . $this->url->createUrl($this->_set, array('action' => 'View', 'id' => $data['foreign_id'])),
        );
        $content = str_replace($search, $replace, $content);


        // 发送邮件
        $mail = Qwin::run('PHPMailer');
        // set mailer to use SMTP
        $mail->IsSMTP();
        // 设置为中文
        $mail->SetLanguage('zh_cn', QWIN_RESOURCE_PATH . '/library/ThirdPart/phpmailer/language/');
        // specify main and backup server
        $mail->Host = 'smtp.qq.com';
        // turn on SMTP authentication
        $mail->SMTPAuth = true;
        // SMTP username
        $mail->Username = 'atwin@qq.com';
        // SMTP password
        $mail->Password = 'hcb6666661';

        $mail->From = $data['from'];
        $mail->FromName = $data['from_name'];
        $mail->AddAddress($data['to'], $data['to']);
        // name is optional
        //$mail->AddAddress("ellen@example.com");
        $mail->AddReplyTo($data['from'], $data['from_name']);
        // set word wrap to 50 characters
        $mail->WordWrap = 50;
        // add attachments
        //$mail->AddAttachment("/var/tmp/file.tar.gz");
        // optional name
        //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
        // set email format to HTML
        $mail->CharSet = 'utf-8';
        $mail->IsHTML(true);

        $mail->Subject = $data['subject'];
        $mail->Body    = $content;

        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
        if(!$mail->Send())
        {
            $this->_mailMessage = $mail->ErrorInfo;
        } else {
            $this->_mailMessage = '已发送';
        }
        return $content;
    }

    public function convertDbEmailResult($value, $name, $data, $copyData)
    {
        return $this->_mailMessage;
    }

    public function isSaveStatusData($data, $query)
    {
        if(isset($data['status']) && isset($query->status) && $data['status'] == $query['status'])
        {
            return false;
        }
        return true;
    }

    public function isSaveEmailData($data, $query)
    {
        $post = $this->request->p('is_post_email');
        if('AssignTo' == $this->getLastAction() && null != $post && 1 == $post[0])
        {
            return true;
        }
        return false;
    }
}
