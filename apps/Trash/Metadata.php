<?php
/**
 * Trash
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

class Com_Trash_Meta extends Com_Meta
{
    public function setMeta()
    {
        $this->setIdMeta();
        $this->setOperationMeta();
        $this->merge(array(
            'field' => array(
                'name' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'module' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'module_id' => array(
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
                    'module' => 'Com/Member',
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
                'table' => 'trash',
                'order' => array(
                    array('deleted_at', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_TRASH',
                'icon' => 'trash',
            ),
        ));
    }

    public function sanitiseListModule($value, $name, $data, $dataCopy)
    {
        $class = Com_Meta::getByModule($value, false);
        if (!class_exists($class)) {
            return $value;
        }
        $meta = Qwin_Meta::getInstance()->get($class);
        $lang = Qwin::call('-lang');
        
        return Qwin_Util_Html::link($this->url->url($value), $lang->t($meta['page']['title']));
    }

    public function sanitiseViewModule($value, $name, $data, $dataCopy)
    {
        return $this->sanitiseListModule($value, $name, $data, $dataCopy);
    }

    public function sanitiseDbDeletedAt($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    public function sanitiseDbDeletedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::call('-session')->get('member');
        return $member['id'];
    }

    public function sanitiseListOperation($value, $name, $data, $dataCopy)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $module = $this->getModule();
        $operation = array();
        $operation['restore'] = array(
            'url' => 'javascript:if(confirm(qwin.lang.MSG_CONFIRM_TO_RESTORE)){window.location=\'' . $url->url($module->toUrl(), 'restore', array($primaryKey => $dataCopy[$primaryKey])) . '\';}',
            'title' => $lang->t('ACT_RESTORE'),
            'icon' => 'ui-icon-arrowreturnthick-1-w',
        );
        $operation += parent::sanitiseListOperation($value, $name, $data, $dataCopy, true);
        $data = '';
        foreach ($operation as $row) {
            $data .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="'
                  . $row['icon'] . '" href="' . $row['url'] . '"><span class="ui-icon ' . $row['icon']
                  .  '">' . $row['icon'] . '</span></a>';
        }
        return $data;
    }
}
