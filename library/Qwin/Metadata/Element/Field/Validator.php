<?php
/**
 * Validator
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-10 19:44:11
 */

class Qwin_Metadata_Element_Field_Validator extends Qwin_Metadata_Abstract
{
    protected $_data = array(
        'required' => array(
            array('Qwin_Validator_Common', 'required'),
            'required'
        ),
        'rangelength' => array(
            array('Qwin_Validator_Common', 'rangelength'),
            0,
            0,
            'rangelength',
        ),
        'maxlength' => array(
            array('Qwin_Validator_Common', 'rangelength'),
            0,
            'maxlength',
        ),
    );

    public function setMap($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function parse($validator)
    {
        foreach($validator as $key => $value)
        {
            if(is_string($value))
            {
                $paramList = explode(',', $value);
                $count = count($paramList);
                // 第一个是验证的名称,后面的都是参数
                for($i = 0; $i < $count; $i++)
                {
                    if(0 == $i)
                    {
                        if(isset($this->_data[$paramList[$i]]))
                        {
                            $validator[$key] = $this->_data[$paramList[$i]];
                        } else {
                            unset($validator[$key]);
                            break;
                        }
                    } else {
                        $validator[$key][$i] = $paramList[$i];
                    }
                }
            }
        }
        return $validator;
    }
}
