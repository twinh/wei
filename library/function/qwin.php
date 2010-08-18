<?php
/**
 * qwin
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
 * @subpackage  Function
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-18 10:28:04
 */

/**
 * 保留函数,不执行任何内容
 *
 * @return boolen
 */
function qw()
{
    return true;
}

/**
 * 快速加载类
 *
 * @param string $name
 * @return object|null
 */
function qwin($name)
{
    return Qwin::run($name);
}

function qw_form()
{

}

function qw_button()
{
    
}

function qw_url()
{

}



