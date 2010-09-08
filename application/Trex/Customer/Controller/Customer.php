<?php
/**
 * Customer
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
 * @subpackage  Customer
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-08 10:55:33
 */

class Trex_Customer_Controller_Customer extends Trex_ActionController
{
    public function actionIndex()
    {
        $this->_meta->model->unlink('contact');
        parent::actionIndex();
    }

    public function actionList()
    {
        $this->_meta->model->unlink('contact');
        parent::actionList();
    }

    public function convertDbContactRelatedModule($value, $name, $data, $copyData)
    {
        return 'customer';
    }
}
