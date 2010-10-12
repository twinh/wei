<?php
/**
 * Email
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
 * @subpackage  Email
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-20 10:37:51
 */

class Trex_Email_Controller_Email extends Trex_ActionController
{
    /**
     * post操作后的地址
     * @var string
     */
    protected $_fromUrl;

    public function actionPost($fromUrl = null)
    {
        $this->_fromUrl = $fromUrl;
        parent::actionAdd();
    }

    public function convertListTo($value, $name, $data, $copyData)
    {
        return $copyData['to_name'] . '&lt;' . $copyData['to'] . '&gt;';
    }

    public function convertListFrom($value, $name, $data, $copyData)
    {
        return $copyData['from_name'] . '&lt;' . $copyData['from'] . '&gt;';
    }

    public function createCustomLink()
    {
        return parent::createCustomLink()
               . Qwin_Helper_Html::jQueryLink($this->url->createUrl($this->_set, array('action' => 'Post')), $this->_lang->t('LBL_ACTION_POST'), 'ui-icon-script');
    }

    public function onAfterDb($data)
    {
        if('Post' == $this->getLastAction())
        {
            // 发布邮件
            $this->_result['result'] = 'posted!';
            $this->_result->save();
            if(null != $this->_fromUrl)
            {
                $this
                    ->setRedirectView('MSG_OPERATE_SUCCESSFULLY', $this->_fromUrl)
                    ->loadView()
                    ->display();
                exit;
            }
            return true;
        }
    }
}