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
                    'asc' => array(
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

    public function filterListType($value, $name, $data, $dataCopy)
    {
        $value = explode('.', $value);
        $asc = array(
            'namespace' => $value[0],
            'module' => $value[1],
            'controller' => $value[2],
        );
        $metadata = $this->metaHelper->getMetadataByAsc($asc);
        $lang = Qwin::call('Common_Helper_Language')->getObjectByAsc($asc);
        
        return Qwin_Util_Html::link($this->url->url($asc), $lang->t($metadata['page']['title']));
    }

    public function filterDbDeletedAt($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    public function filterDbDeletedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::call('-session')->get('member');
        return $member['id'];
    }

    public function filterListOperation($value, $name, $data, $dataCopy)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $asc = $this->getAscFromClass();
        $operation = array();
        $operation['restore'] = array(
            'url' => 'javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_RESTORE)){window.location=\'' . $url->url($asc, array('action' => 'Restore', $primaryKey => $dataCopy[$primaryKey])) . '\';}',
            'title' => $lang->t('LBL_ACTION_RESTORE'),
            'icon' => 'ui-icon-arrowreturnthick-1-w',
        );
        $operation += parent::filterListOperation($value, $name, $data, $dataCopy, true);
        $data = '';
        foreach ($operation as $row) {
            $data .= Qwin_Util_Html::jQueryButton($row['url'], $row['title'], $row['icon']);
        }
        return $data;
    }
}
