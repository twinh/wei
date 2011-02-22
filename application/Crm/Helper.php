<?php
/**
 * Helper
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
 * @since       2011-01-14 15:10:13
 */

class Crm_Helper
{
    public static function sanitisePopupMember($value, $name, $viewName, $meta)
    {
        $data = Qwin::call('Qwin_Application_Metadata')
            ->getQueryByAsc(array(
                'namespace' => 'Common',
                'module' => 'Member',
                'controller' => 'Member',
            ))
            ->where('id = ?', $value)
            ->fetchOne();
        $meta['field']->set($name . '.form._value2', $data[$viewName]);
    }

    public static function sanitisePopupContact($value, $name, $viewName, $meta)
    {
        $data = Qwin::call('Qwin_Application_Metadata')
            ->getQueryByAsc(array(
                'namespace' => 'Crm',
                'module' => 'Contact',
                'controller' => 'Contact',
            ))
            ->where('id = ?', $value)
            ->fetchOne();
        $meta['field']->set($name . '.form._value2', $data['last_name'] . $data['first_name']);
    }

    public static function sanitisePopupCustomer($value, $name, $viewName, $meta)
    {
        $data = Qwin::call('Qwin_Application_Metadata')
            ->getQueryByAsc(array(
                'namespace' => 'Crm',
                'module' => 'Customer',
                'controller' => 'Customer',
            ))
            ->where('id = ?', $value)
            ->fetchOne();
        $meta['field']->set($name . '.form._value2', $data[$viewName]);
    }

    public static function sanitisePopupOpportunity($value, $name, $viewName, $meta)
    {
        $data = Qwin::call('Qwin_Application_Metadata')
            ->getQueryByAsc(array(
                'namespace' => 'Crm',
                'module' => 'Opportunity',
                'controller' => 'Opportunity',
            ))
            ->where('id = ?', $value)
            ->fetchOne();
        $meta['field']->set($name . '.form._value2', $data[$viewName]);
    }
}
