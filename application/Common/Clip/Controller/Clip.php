<?php
/**
 * Clip
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
 * @subpackage  Clip
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-06-02
 */

class Common_Clip_Controller_Clip extends Common_ActionController
{
    public function onAfterDb()
    {
        $query = $this->metaHelper->getQuery($this->_meta);
        $data = $query->execute()->toArray();
        $cache = array();
        foreach($data as $row)
        {
            $cache[$row['name']] = $row['value'];
        }
        Qwin::run('Qwin_Cache_List')->writeCache($cache, 'clip');
    }
}
