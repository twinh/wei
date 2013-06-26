<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese plate number
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://zh.wikipedia.org/wiki/%E4%B8%AD%E5%8D%8E%E4%BA%BA%E6%B0%91%E5%85%B1%E5%92%8C%E5%9B%BD%E8%BD%A6%E8%BE%86%E5%8F%B7%E7%89%8C
 */
class PlateNumberCn extends Regex
{
    protected $patternMessage = '%name% must be valid Chinese plate number';
    
    protected $negativeMessage = '%name% must not be valid Chinese plate number';
    
    protected $pattern = '/^[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼渝川贵云藏陕甘青宁新军海空北沈兰济南广成][A-Z]{1}[A-Z0-9]{5}$/ui';
}
