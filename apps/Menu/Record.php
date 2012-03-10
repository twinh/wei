<?php
/**
 * Menu
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2010-05-25 08:10:46
 */
class Menu_Record extends Qwin_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('menu');

        $this->hasColumn('id');

        $this->hasColumn('category_id');

        $this->hasColumn('title');

        $this->hasColumn('url');

        $this->hasColumn('target');

        $this->hasColumn('order');
    }

    public function setUp()
    {
        $this->hasOne(__CLASS__ . ' as category', array(
                'local' => 'category_id',
                'foreign' => 'id'
            )
        );
    }

    public function getCagetoryOptions()
    {
        $categorys = Qwin::getInstance()->query()
            ->from(__CLASS__)
            ->where('category_id is null')
            ->fetchArray();

        $options = array();
        $options[0] = '请选择';
        foreach ($categorys as $category) {
            $options[$category['id']] = $category['title'];
        }
        return $options;
    }
}
