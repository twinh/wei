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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-02 23:58:22
 */

class Public_View extends Qwin_Trex_View
{
    public function __construct()
    {
        Qwin::addMap('-jquery', 'Qwin_Resource_JQuery');
        $this->_jquery = Qwin::run('-jquery');

        $this->_layout = './view/layout.php';
    }

    public function display()
    {
        $this->_link = Qwin::run('Qwin_Trex_Metadata')
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Link',
                'controller' => 'Link',
            ));

        $this->headerLink = $this
            ->_link
            ->where('category_id = ?', 'header')
            ->execute();
        $this->footerLink = $this
            ->_link
            ->where('category_id = ?', 'footer')
            ->execute();

        extract($this->_data);
        require_once $this->_layout;
        return $this;
    }
}
