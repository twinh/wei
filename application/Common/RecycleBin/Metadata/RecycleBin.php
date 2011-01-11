<?php
/**
 * RecycleBin
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
 * @since       2011-01-09 14:48:52
 */

class Common_RecycleBin_Metadata_RecycleBin extends Common_Metadata
{
    public function setMetadata()
    {
        $this->setIdMetadata();
        $this->setOperationMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'name' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'type' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'type_id' => array(
                    'form' => array(
                        '_type' => 'hidden'
                    ),
                    'attr' => array(
                        'isView' => 0,
                    ),
                ),
                'deleted_by' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'deleted_at' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
            ),
            'group' => array(

            ),
            'layout' => array(

            ),
            'model' => array(
                'deleter' => array(
                    'set' => array(
                        'namespace' => 'Common',
                        'module' => 'Member',
                        'controller' => 'Member',
                    ),
                    'alias' => 'deleter',
                    'local' => 'deleted_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'deleted_by' => 'username',
                    ),''
                ),
            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'recyclebin',
                'order' => array(
                    array('deleted_at', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_RECYCLEBIN',
                'icon' => 'trash',
            ),
        ));
    }

    public function convertListType($value, $name, $data, $dataCopy)
    {
        $value = explode('.', $value);
        $asc = array(
            'namespace' => $value[0],
            'module' => $value[1],
            'controller' => $value[2],
        );
        $metadata = $this->metaHelper->getMetadataByAsc($asc);

        // 加载语言 TODO !!
        $languageResult = Qwin::run('Common_Service_Language')->getLanguage($asc);
        $languageName = $languageResult['data'];
        $languageClass = $asc['namespace'] . '_' . $asc['module'] . '_Language_' . $languageName;
        $language = Qwin::run($languageClass);
        if(null == $language)
        {
            $languageClass = 'Common_Language_' . $languageName;
            $language = Qwin::run($languageClass);
        }
        return Qwin_Helper_Html::link($this->url->createUrl($asc), $language->t($metadata['page']['title']));
    }

    public function convertDbDeletedAt($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    public function convertDbDeletedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::run('-session')->get('member');
        return $member['id'];
    }

    public function convertListOperation($value, $name, $data, $dataCopy)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $asc = $this->getAscFromClass();
        $operation = array();
        $operation['restore'] = array(
            'url' => 'javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_RESTORE)){window.location=\'' . $url->createUrl($asc, array('action' => 'Restore', $primaryKey => $dataCopy[$primaryKey])) . '\';}',
            'title' => $lang->t('LBL_ACTION_RESTORE'),
            'icon' => 'ui-icon-arrowreturnthick-1-w',
        );
        $operation += parent::convertListOperation($value, $name, $data, $dataCopy, true);
        $data = '';
        foreach ($operation as $row) {
            $data .= Qwin_Helper_Html::jQueryButton($row['url'], $row['title'], $row['icon']);
        }
        return $data;
    }
}
