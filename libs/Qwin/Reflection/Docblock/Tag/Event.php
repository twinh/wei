<?php
/**
 * Event
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-09-10 18:11:31
 */

class Qwin_Reflection_Docblock_Tag_Event extends Qwin_Reflection_Docblock_Tag
{
    /**
     * @var string
     */
    protected $_type = null;

    /**
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Constructor
     *
     * @param string $tagDocblockLine
     */
    public function __construct($tagDocblockLine)
    {
        $matches = array();
        
        if (!preg_match('#^@(\w+)\s+'   // "@event "
                      . '(\w+)?\s+'     // "type "
                      . '(\w+)[\s+]?'   // "methodName"
                      . '(\(.+?)$#',    // (type $param, type2 $param2) descroption
                        $tagDocblockLine, $matches)) {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Provided docblock line is does not contain a valid tag');
        }
        
        $this->_type = $matches[2];
        
        // TODO regard as a ReflectionFunction object?
        $this->_methodName = $matches[3];
        
        // try to catch params
        $tokens = @token_get_all('<?php ' . $matches[4]);
        
        // remove '<?php'
        //array_shift($tokens);
        
        // store the code length for exception and catch the descption
        $offset = 0;
        
        // TODO as a ReflectionParameter object?
        $i = 0;
        $params[0] = array(
            'type' => null,
            'name' => null,
            'value' => null,
        );
        
        // left and right parentheses, should be equal or throw an exception after analysed
        $leftParenthesis = 0;
        $rightParenthesis = 0;
        
        // excepted type for next token
        $expectations = array();

        // TODO clean up
        while(next($tokens)) {
            $token = current($tokens);
            $value = isset($token[1]) ? $token[1] : $token[0];
            $offset += strlen($value);

            // skip whitespaces
            if (T_WHITESPACE === $token[0]) {
                continue;
            }
            
            if (!empty($expectations) && !in_array($token[0], $expectations)) {
                require_once 'Zend/Reflection/Exception.php';
                throw new Zend_Reflection_Exception('Parse error: syntax error, unexpected \'' . $value . '\'');
            }
            
            if (T_VARIABLE == $token[0]) {
                $params[$i]['name'] = $token[1];
                $expectations = array(
                    '=', ',', ')',
                );
                continue;
            }
            
            if (')' === $token[0]) {
                if (++$rightParenthesis == $leftParenthesis) {
                    break;
                }
                continue;
            }

            if (',' === $token[0] || '(' === $token[0]) {
                if ('(' === $token[0]) {
                    $leftParenthesis++;
                } else {
                    $i++;
                }
                $params[$i] = array(
                    'type' => null,
                    'name' => null,
                    'value' => null,
                );
                // catch "type" until "var"
                // should i check the type string to avoid error like T_ENCAPSED_AND_WHITESPACE ?
                while (next($tokens)) {
                    $token = current($tokens);
                    if (T_VARIABLE !== $token[0]) {
                        $value = isset($token[1]) ? $token[1] : $token[0];
                        $offset += strlen($value);
                        $params[$i]['type'] .= $value;
                    } else {
                        prev($tokens);
                        $expectations = array(
                            T_VARIABLE,
                        );
                        break;
                    }
                }
                continue;
            }
            
            
            if ('=' === $token[0]) {
                while (next($tokens)) {
                    $token = current($tokens);
                    $value = isset($token[1]) ? $token[1] : $token[0];
                    $offset += strlen($value);
                    switch ($token[0]) {
                        // null, E_NOTICE, true
                        case T_STRING:
                        
                        // 1.1
                        case T_DNUMBER:
                            
                        // 12
                        case T_LNUMBER:
                            
                        // "str", 'str'
                        case T_CONSTANT_ENCAPSED_STRING:
                            $params[$i]['value'] = $token[1];
                            $i++;
                            $expectations = array(
                                T_STRING, T_VARIABLE, ')', ',',
                            );
                            break 2;
                        // catch the array content
                        case T_ARRAY:
                            $leftParenthesis1 = 0;
                            $rightParenthesis1 = 0;
                            $content = isset($token[1]) ? $token[1] : $token[0];
                            $expectations = array(
                                ',', ')',
                            );
                            while (next($tokens)) {
                                $token = current($tokens);
                                $value = isset($token[1]) ? $token[1] : $token[0];
                                $offset += strlen($value);
                                $content .= $value;
                                
                                // skip whitespaces
                                if (T_WHITESPACE === $token[0]) {
                                    continue;
                                }
                                
                                if ('(' === $token[0]) {
                                    $leftParenthesis1++;
                                } elseif (')' == $token[0]) {
                                    if (++$rightParenthesis1 == $leftParenthesis1) {
                                        $params[$i]['value'] = $content;
                                        break;
                                    }
                                }
                            }
                            break 2;
                    }
                }
                continue;
            }
            
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Parse error: syntax error, unexpected \'' . $value . '\'');
        }
        
        if ($leftParenthesis != $rightParenthesis) {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Parse error: unmatched parentheses.');
        }

        // catch description
        $this->_parseDescription(trim(substr($matches[4], $offset + 1)));
        
        $this->_methodName = $matches[3];
        $this->_params = $params;
        $this->_name = 'event';
        $this->_type = $matches[2];
    }

    /**
     * Get parameter variable type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * Get parameter array
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    /**
     * Get method name
     *
     * @return array
     */
    public function getMethodName()
    {
        return $this->_methodName;
    }
    
    public function toArray()
    {
        $tmp = array();
        foreach ($this->getParams() as $param) {
            if (isset($param['value'])) {
                $tmp[] = $param['type'] . $param['name'] . ' = ' . $param['value'];
            } else {
                $tmp[] = $param['type'] . $param['name'];
            }
        }
        
        return array(
            'name' => $this->getMethodName(),
            'param' => '(' . implode(', ', $tmp) . ')',
            'return' => $this->getType(),
            'description' => $this->getDescription(),
        );
    }
}