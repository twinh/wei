<?php
/**
 * Page
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
 * @package     Project
 * @subpackage  Hepler
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-03 8:56:24
 */

class Project_Hepler_Page
{
    public function __construct()
    {
        
    }

    public static function create(array $page)
    {
        $pageSample = array(
            'url' => null,
            'name' => 'page',
            'firstPage' => 1,
            'prePage' => null,
            'nextPage' => null,
            'nowPage' => 1,
            'totalPage' => null,
            'row' => 10,
            'count' => null,
        );
        $page = $page + $pageSample;
        
        $page['url'] .= '&' . $page['name'] . '=';

        $page['totalPage'] = ceil($page['count'] / $page['row']);

        $page['nowPage'] > $page['totalPage'] && $page['nowPage'] = $page['totalPage'];

        $page['prePage'] = $page['nowPage'] - 1;
        $page['prePage'] <= 0 && $page['prePage'] = 1;

        $page['nextPage'] = $page['nowPage'] + 1;
        $page['nextPage'] >= $page['totalPage'] && $page['nextPage'] = $page['totalPage'];

        $html = '<a href="' . $page['url'] . $page['firstPage'] . '">首页</a>
            <a href="' . $page['url'] . $page['prePage'] . '">上一页</a>
            <a href="' . $page['url'] . $page['nextPage'] . '">下一页</a>
            <a href="' . $page['url'] . $page['totalPage'] . '">尾页</a>页次：' . $page['nowPage'] . '/' . $page['totalPage'];
        return $html;
    }
}
